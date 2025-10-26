<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use Carbon\Carbon;

class EcheanceCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $credit = Credit::query()->first();
        if (!$credit) { return; }

        // Skip if schedule already exists
        if ($credit->echeances()->exists()) { return; }

        $principal = (float)($credit->montant_accorde ?? $credit->montant_demande ?? 0);
        $n = max(1, (int)$credit->duree);
        $annualRate = (float)($credit->taux ?? 0) / 100.0;

        $periodsPerYear = match ($credit->periodicite) {
            'journalier' => 360,
            'hebdomadaire' => 52,
            default => 12,
        };
        $i = $annualRate / $periodsPerYear;

        // Annuity payment per period
        if ($i > 0) {
            $payment = $principal * ($i * pow(1 + $i, $n)) / (pow(1 + $i, $n) - 1);
        } else {
            $payment = $principal / $n;
        }
        $payment = round($payment, 2);

        $startDate = $credit->date_debut_remboursement ?: now()->toDateString();
        $date = Carbon::parse($startDate);

        for ($k = 1; $k <= $n; $k++) {
            EcheanceCredit::create([
                'credit_id' => $credit->id,
                'numero_echeance' => $k,
                'date_echeance' => $date->toDateString(),
                'montant_attendu' => $payment,
                'montant_paye' => 0,
                'penalite_appliquee' => 0,
                'statut' => 'en_attente',
            ]);

            switch ($credit->periodicite) {
                case 'journalier': $date->addDay(); break;
                case 'hebdomadaire': $date->addWeek(); break;
                default: $date->addMonth(); break;
            }
        }
    }
}
