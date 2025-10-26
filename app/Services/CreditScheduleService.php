<?php

namespace App\Services;

use App\Models\Credit;
use App\Models\EcheanceCredit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreditScheduleService
{
    public function generateSchedule(Credit $credit): void
    {
        // Preconditions
        if (!$credit->montant_accorde || $credit->montant_accorde <= 0) {
            throw new \InvalidArgumentException('Montant accordé requis pour générer l\'échéancier.');
        }
        if (!$credit->periodicite) {
            throw new \InvalidArgumentException('Périodicité requise pour générer l\'échéancier.');
        }
        if (!$credit->date_debut_remboursement) {
            throw new \InvalidArgumentException('Date de début de remboursement requise.');
        }
        if (!$credit->duree || $credit->duree <= 0) {
            throw new \InvalidArgumentException('Durée invalide.');
        }

        $periodsPerYear = match ($credit->periodicite) {
            'journalier' => 360,
            'hebdomadaire' => 52,
            default => 12, // mensuel
        };

        $P = (float) $credit->montant_accorde;
        $n = (int) $credit->duree; // already in number of periods
        $annualRate = (float) $credit->taux; // percent
        $i = ($annualRate / 100.0) / $periodsPerYear;

        // Annuity payment A (without fees)
        if ($i > 0) {
            $A = $P * $i / (1 - pow(1 + $i, -$n));
        } else {
            $A = $P / $n; // zero rate
        }

        // Round to cents
        $A = round($A, 2);

        DB::transaction(function () use ($credit, $n, $i, $A) {
            // Reset previous schedule
            $credit->echeances()->delete();

            $date = Carbon::parse($credit->date_debut_remboursement);
            $solde = (float) $credit->montant_accorde;
            $totalCapitalCree = 0.0;

            for ($k = 1; $k <= $n; $k++) {
                $interet = round($solde * $i, 2);
                $capital = round($A - $interet, 2);

                // Ensure last period adjusts rounding so total capital equals principal
                if ($k === $n) {
                    $capital = round($solde, 2); // whatever remains
                    $A_k = round($capital + $interet, 2);
                } else {
                    $A_k = $A;
                }

                $montantAttendu = $A_k;

                // Add fees on first installment (Option A)
                if ($k === 1) {
                    $montantAttendu = round($montantAttendu + (float) $credit->frais_adhesion + (float) $credit->frais_dossier, 2);
                }

                EcheanceCredit::create([
                    'credit_id' => $credit->id,
                    'numero_echeance' => $k,
                    'date_echeance' => $date->copy(),
                    'montant_attendu' => $montantAttendu,
                    'montant_paye' => 0,
                    'penalite_appliquee' => 0,
                    'statut' => 'en_attente',
                    'date_paiement' => null,
                ]);

                $solde = round($solde - $capital, 2);
                $totalCapitalCree += $capital;

                // Increment date based on periodicity
                $date = match ($credit->periodicite) {
                    'journalier' => $date->copy()->addDay(),
                    'hebdomadaire' => $date->copy()->addWeek(),
                    default => $date->copy()->addMonth(),
                };
            }
        });
    }
}
