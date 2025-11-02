<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'nom' => 'Épargne Classique',
                'description' => 'Plan d\'épargne de base avec versements mensuels',
                'periodicite' => 'mensuel',
                'montant_min' => 5000,
                'montant_max' => 100000,
                'taux_interet' => 3,
                'duree_min_jours' => 90,
                'frais_adhesion' => 1000,
                'frais_retrait' => 500,
                'actif' => true,
                'ordre_affichage' => 1,
                'conditions' => json_encode([
                    'retrait_partiel_autorise' => true,
                    'duree_engagement' => '3 mois minimum',
                    'penalite_retrait_anticipé' => '2% du montant'
                ])
            ],
            
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['nom' => $plan['nom']],
                $plan
            );
        }
    }
}