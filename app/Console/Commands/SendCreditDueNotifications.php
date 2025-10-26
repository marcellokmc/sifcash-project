<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use App\Models\Notification;
use Carbon\Carbon;

class SendCreditDueNotifications extends Command
{
    protected $signature = 'credits:notify-due {--dry-run : Show what would be sent without creating notifications}';

    protected $description = 'Send notifications for upcoming and overdue credit installments to adherents.';

    public function handle(): int
    {
        $dryRun = (bool)$this->option('dry-run');
        $today = Carbon::today();
        $j1 = $today->copy()->addDay();
        $j3 = $today->copy()->addDays(3);

        // Only active credits
        $credits = Credit::where('etat', 'actif')->with(['adherent.user'])->get();
        $created = 0;

        foreach ($credits as $credit) {
            $user = $credit->adherent?->user;
            if (!$user) { continue; }

            // Upcoming: J-3 and J-1
            $upcoming = EcheanceCredit::where('credit_id', $credit->id)
                ->where('statut', 'en_attente')
                ->whereIn('date_echeance', [$j3->toDateString(), $j1->toDateString()])
                ->get();

            foreach ($upcoming as $ech) {
                $days = Carbon::parse($ech->date_echeance)->diffInDays($today, false);
                // days negative means in the future; we want absolute remaining days
                $remaining = Carbon::parse($ech->date_echeance)->diffInDays($today, false) < 0
                    ? $today->diffInDays(Carbon::parse($ech->date_echeance)) : 0;

                $message = sprintf(
                    'Rappel: échéance du %s. Montant dû: %0.2f FCFA%s',
                    Carbon::parse($ech->date_echeance)->format('d/m/Y'),
                    (float)$ech->montant_attendu,
                    ((float)$ech->penalite_appliquee > 0 ? ' + pénalités' : '')
                );

                if (!$dryRun) {
                    Notification::create([
                        'user_id' => $user->id,
                        'titre' => 'Rappel échéance crédit',
                        'message' => $message,
                        'lu' => false,
                        'type' => 'info',
                    ]);
                    $created++;
                }
            }

            // Overdue: statut en_retard non payé
            $overdue = EcheanceCredit::where('credit_id', $credit->id)
                ->where('statut', 'en_retard')
                ->get();

            foreach ($overdue as $ech) {
                $totalDue = (float)$ech->montant_attendu + max(0, (float)$ech->penalite_appliquee);
                $message = sprintf(
                    'Échéance en retard depuis le %s. Total dû: %0.2f FCFA (pénalités incluses).',
                    Carbon::parse($ech->date_echeance)->format('d/m/Y'),
                    $totalDue
                );
                if (!$dryRun) {
                    Notification::create([
                        'user_id' => $user->id,
                        'titre' => 'Échéance en retard',
                        'message' => $message,
                        'lu' => false,
                        'type' => 'alert',
                    ]);
                    $created++;
                }
            }
        }

        $this->info("Notifications created: {$created}");
        return Command::SUCCESS;
    }
}
