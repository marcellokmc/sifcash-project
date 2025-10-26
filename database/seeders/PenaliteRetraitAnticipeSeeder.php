<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenaliteRetraitAnticipe;

class PenaliteRetraitAnticipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenaliteRetraitAnticipe::firstOrCreate(
            [
                'pourcentage_capital_requis' => 50.00,
                'duree_preavis_jours' => 7,
            ],
            [
                'taux_penalite' => 5.00,
                'actif' => true,
            ]
        );
    }
}
