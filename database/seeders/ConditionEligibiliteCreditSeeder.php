<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConditionEligibiliteCredit;

class ConditionEligibiliteCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            [
                'type_cotisation_requise' => 'journalier',
                'duree_minimum_anciennete' => 30, // jours équivalents
                'montant_epargne_minimum' => 0,
                'actif' => true,
            ],
            [
                'type_cotisation_requise' => 'hebdomadaire',
                'duree_minimum_anciennete' => 4, // semaines
                'montant_epargne_minimum' => 0,
                'actif' => true,
            ],
            [
                'type_cotisation_requise' => 'mensuel',
                'duree_minimum_anciennete' => 3, // mois
                'montant_epargne_minimum' => 0,
                'actif' => true,
            ],
        ];

        foreach ($defaults as $data) {
            ConditionEligibiliteCredit::firstOrCreate(
                [
                    'type_cotisation_requise' => $data['type_cotisation_requise'],
                    'duree_minimum_anciennete' => $data['duree_minimum_anciennete'],
                ],
                $data
            );
        }
    }
}
