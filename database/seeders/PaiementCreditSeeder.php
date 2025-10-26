<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use App\Models\PaiementCredit;
use App\Models\PaiementCreditPreuve;
use Carbon\Carbon;

class PaiementCreditSeeder extends Seeder
{
    public function run(): void
    {
        $credit = Credit::query()->with('echeances')->first();
        if (!$credit) { return; }

        // Ensure there is a schedule
        if ($credit->echeances()->count() < 2) { return; }

        $e1 = $credit->echeances()->orderBy('date_echeance')->first();
        $e2 = $credit->echeances()->orderBy('date_echeance')->skip(1)->first();

        // Partial payment on first installment
        if ($e1 && $e1->montant_paye <= 0) {
            $p1Amount = max(0.0, (float)$e1->montant_attendu * 0.5);
            $p1 = PaiementCredit::create([
                'credit_id' => $credit->id,
                'echeance_credit_id' => $e1->id,
                'date_paiement' => Carbon::parse($e1->date_echeance)->subDays(2)->toDateString(),
                'montant' => round($p1Amount, 2),
                'penalite' => 0,
                'mode' => 'espece',
                'reference' => 'SEED-P1-'.uniqid(),
                'received_by_agent_id' => null,
                'statut' => 'valide',
            ]);

            // Attach a dummy proof record (path placeholder)
            PaiementCreditPreuve::create([
                'paiement_credit_id' => $p1->id,
                'type' => 'reçu',
                'path' => 'storage/paiements/preuves/seed_p1.pdf',
                'original_name' => 'seed_p1.pdf',
            ]);

            $e1->montant_paye = ($e1->montant_paye ?? 0) + $p1->montant;
            if ($e1->montant_paye + 0.001 >= ($e1->montant_attendu + max(0,(float)$e1->penalite_appliquee))) {
                $e1->statut = 'payé';
                $e1->date_paiement = $p1->date_paiement;
            }
            $e1->save();
        }

        // Full payment on second installment
        if ($e2 && $e2->montant_paye <= 0) {
            $due2 = (float)$e2->montant_attendu + max(0,(float)$e2->penalite_appliquee);
            $p2 = PaiementCredit::create([
                'credit_id' => $credit->id,
                'echeance_credit_id' => $e2->id,
                'date_paiement' => Carbon::parse($e2->date_echeance)->toDateString(),
                'montant' => round($due2, 2),
                'penalite' => 0,
                'mode' => 'virement',
                'reference' => 'SEED-P2-'.uniqid(),
                'received_by_agent_id' => null,
                'statut' => 'valide',
            ]);

            $e2->montant_paye = ($e2->montant_paye ?? 0) + $p2->montant;
            if ($e2->montant_paye + 0.001 >= ($e2->montant_attendu + max(0,(float)$e2->penalite_appliquee))) {
                $e2->statut = 'payé';
                $e2->date_paiement = $p2->date_paiement;
            }
            $e2->save();
        }
    }
}
