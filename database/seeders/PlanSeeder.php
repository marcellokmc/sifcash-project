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
                'taux_interet' => 3.5,
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
            [
                'nom' => 'Épargne Plus',
                'description' => 'Plan d\'épargne avec taux d\'intérêt préférentiel',
                'periodicite' => 'mensuel',
                'montant_min' => 10000,
                'montant_max' => 200000,
                'taux_interet' => 5.0,
                'duree_min_jours' => 180,
                'frais_adhesion' => 2000,
                'frais_retrait' => 1000,
                'actif' => true,
                'ordre_affichage' => 2,
                'conditions' => json_encode([
                    'retrait_partiel_autorise' => false,
                    'duree_engagement' => '6 mois minimum',
                    'penalite_retrait_anticipé' => '5% du montant'
                ])
            ],
            [
                'nom' => 'Épargne Express',
                'description' => 'Plan court terme pour épargne rapide',
                'periodicite' => 'hebdomadaire',
                'montant_min' => 2000,
                'montant_max' => 50000,
                'taux_interet' => 2.0,
                'duree_min_jours' => 30,
                'frais_adhesion' => 500,
                'frais_retrait' => 200,
                'actif' => true,
                'ordre_affichage' => 3,
                'conditions' => json_encode([
                    'retrait_partiel_autorise' => true,
                    'duree_engagement' => '1 mois minimum',
                    'penalite_retrait_anticipé' => '1% du montant'
                ])
            ],
            [
                'nom' => 'Épargne Long Terme',
                'description' => 'Plan d\'épargne pour projet à long terme',
                'periodicite' => 'trimestriel',
                'montant_min' => 20000,
                'montant_max' => 500000,
                'taux_interet' => 7.5,
                'duree_min_jours' => 365,
                'frais_adhesion' => 5000,
                'frais_retrait' => 2000,
                'actif' => true,
                'ordre_affichage' => 4,
                'conditions' => json_encode([
                    'retrait_partiel_autorise' => false,
                    'duree_engagement' => '1 an minimum',
                    'penalite_retrait_anticipé' => '10% du montant'
                ])
            ],
            [
                'nom' => 'Épargne Projet Scolaire',
                'description' => 'Plan dédié aux dépenses scolaires annuelles',
                'periodicite' => 'mensuel',
                'montant_min' => 3000,
                'montant_max' => 150000,
                'taux_interet' => 4.0,
                'duree_min_jours' => 180,
                'frais_adhesion' => 1500,
                'frais_retrait' => 500,
                'actif' => true,
                'ordre_affichage' => 5,
                'conditions' => json_encode([
                    'retrait_partiel_autorise' => true,
                    'duree_engagement' => '6 mois minimum',
                    'penalite_retrait_anticipé' => '3% du montant'
                ])
            ]
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['nom' => $plan['nom']],
                $plan
            );
        }
    }
}