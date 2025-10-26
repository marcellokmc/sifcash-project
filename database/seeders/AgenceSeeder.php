<?php

namespace Database\Seeders;

use App\Models\Agence;
use Illuminate\Database\Seeder;

class AgenceSeeder extends Seeder
{
    public function run()
    {
        $agences = [
            [
                'code' => 'OUAGA_01',
                'nom' => 'Agence Ouagadougou Centre',
                'province' => 'Kadiogo',
                'departement' => 'Ouagadougou',
                'adresse' => 'Avenue de la Revolution, Ouagadougou',
                'contact' => '+226 25 45 67 89',
                'active' => true
            ],
            [
                'code' => 'BOBO_01',
                'nom' => 'Agence Bobo-Dioulasso',
                'province' => 'Hauts-Bassins',
                'departement' => 'Bobo-Dioulasso',
                'adresse' => 'Rue de la Commerce, Bobo-Dioulasso',
                'contact' => '+226 20 45 67 90',
                'active' => true
            ],
            [
                'code' => 'KOUDOUGOU_01',
                'nom' => 'Agence Koudougou',
                'province' => 'Boulkiemdé',
                'departement' => 'Koudougou',
                'adresse' => 'Avenue du Conseil, Koudougou',
                'contact' => '+226 25 40 12 34',
                'active' => true
            ],
            [
                'code' => 'OUAHIGOUYA_01',
                'nom' => 'Agence Ouahigouya',
                'province' => 'Yatenga',
                'departement' => 'Ouahigouya',
                'adresse' => 'Quartier Kossoghin, Ouahigouya',
                'contact' => '+226 25 41 22 33',
                'active' => true
            ],
            [
                'code' => 'FADA_01',
                'nom' => 'Agence Fada N’Gourma',
                'province' => 'Gourma',
                'departement' => 'Fada N’Gourma',
                'adresse' => 'Avenue de l’Indépendance, Fada',
                'contact' => '+226 25 42 55 66',
                'active' => true
            ]
        ];

        foreach ($agences as $agence) {
            Agence::updateOrCreate(
                ['code' => $agence['code']],
                $agence
            );
        }
    }
}