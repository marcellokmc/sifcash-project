<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adherent;
use App\Models\Credit;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adherent = Adherent::first();
        if (!$adherent) {
            return; // No adherent to attach test credit
        }

        Credit::firstOrCreate([
            'adherent_id' => $adherent->id,
            'montant_demande' => 100000,
            'duree' => 12,
            'taux' => 12.00,
            'type_credit' => 'personnel',
            'periodicite' => 'mensuel',
            'statut' => 'en_attente',
            'etat' => 'soumis',
            'date_demande' => now(),
        ]);
    }
}
