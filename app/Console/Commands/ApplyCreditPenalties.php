<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApplyCreditPenalties extends Command
{
    protected $signature = 'credits:apply-penalties {--dry-run : Show what would change without saving}';

    protected $description = 'Mark overdue installments and apply late penalties based on credit settings.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $now = Carbon::today();

        // Process only active credits
        $query = Credit::query()->where('etat', 'actif');
        $countCredits = $query->count();
        $this->info("Processing {$countCredits} active credits...");

        $processed = 0;
        $affectedEcheances = 0;

        $query->chunkById(100, function ($credits) use (&$processed, &$affectedEcheances, $now, $dryRun) {
            foreach ($credits as $credit) {
                $periodsPerYear = match ($credit->periodicite) {
                    'journalier' => 360,
                    'hebdomadaire' => 52,
                    default => 12,
                };

                $intervalSize = match ($credit->periodicite) {
                    'journalier' => 'day',
                    'hebdomadaire' => 'week',
                    default => 'month',
                };

                $tauxPenalite = (float) ($credit->taux_penalite ?? 0.0);
                $modePenalite = $credit->mode_penalite ?? 'pourcentage';

                // Find overdue or due installments that are not fully paid
                $echeances = EcheanceCredit::where('credit_id', $credit->id)
                    ->whereIn('statut', ['en_attente', 'en_retard'])
                    ->get();

                foreach ($echeances as $ech) {
                    $dueDate = Carbon::parse($ech->date_echeance)->startOfDay();
                    $isPastDue = $now->greaterThan($dueDate) && ($ech->montant_paye + 0.0001) < ($ech->montant_attendu + max(0, (float)$ech->penalite_appliquee) - 0.0001);

                    // Update status if past due and not fully paid
                    if ($isPastDue && $ech->statut !== 'payé') {
                        $ech->statut = 'en_retard';
                    }

                    if ($isPastDue && $tauxPenalite > 0) {
                        // Compute number of intervals overdue
                        $intervals = 0;
                        switch ($intervalSize) {
                            case 'day':
                                $intervals = max(1, $dueDate->diffInDays($now));
                                break;
                            case 'week':
                                $intervals = max(1, $dueDate->diffInWeeks($now));
                                break;
                            default:
                                $intervals = max(1, $dueDate->diffInMonths($now));
                                break;
                        }

                        $remainingBase = max(0.0, (float) $ech->montant_attendu - (float) $ech->montant_paye);

                        if ($modePenalite === 'fixe') {
                            // Fixed amount per interval
                            $penaliteCumulative = round($tauxPenalite * $intervals, 2);
                        } else {
                            // Percentage per interval applied on remaining due of the installment
                            $penaliteCumulative = round($remainingBase * ($tauxPenalite / 100.0) * $intervals, 2);
                        }

                        // Set cumulative penalty; do not decrease if lower (safety)
                        if ($penaliteCumulative > (float) $ech->penalite_appliquee) {
                            $ech->penalite_appliquee = $penaliteCumulative;
                        }
                    }

                    if (!$dryRun) {
                        if ($ech->isDirty()) {
                            $ech->save();
                            $affectedEcheances++;
                        }
                    }
                }

                $processed++;
            }
        });

        $this->info("Processed batches: {$processed}; Updated installments: {$affectedEcheances}");
        return Command::SUCCESS;
    }
}
