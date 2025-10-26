<?php

namespace Database\Factories;

use App\Models\EcheanceCredit;
use App\Models\Credit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EcheanceCreditFactory extends Factory
{
    protected $model = EcheanceCredit::class;

    public function definition(): array
    {
        $credit = Credit::factory()->create();
        $date = Carbon::now()->addMonth();
        return [
            'credit_id' => $credit->id,
            'date_echeance' => $date->toDateString(),
            'montant_attendu' => $this->faker->randomFloat(2, 5000, 50000),
            'montant_paye' => 0,
            'penalite_appliquee' => 0,
            'statut' => 'en_attente',
            'date_paiement' => null,
        ];
    }
}
