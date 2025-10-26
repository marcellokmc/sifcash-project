<?php

namespace Database\Factories;

use App\Models\Credit;
use App\Models\Adherent;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    protected $model = Credit::class;

    public function definition(): array
    {
        $adherent = Adherent::factory()->create();
        $periodicites = ['journalier','hebdomadaire','mensuel'];
        $typeCredits = ['personnel','éducation','urgence'];
        $periodicite = $this->faker->randomElement($periodicites);

        return [
            'adherent_id' => $adherent->id,
            'montant_demande' => $this->faker->randomFloat(2, 50000, 500000),
            'duree' => $this->faker->numberBetween(3, 24),
            'taux' => $this->faker->randomFloat(2, 5, 20),
            'montant_accorde' => null,
            'type_credit' => $this->faker->randomElement($typeCredits),
            'statut' => 'en_attente',
            'motif_rejet' => null,
            'date_demande' => now(),
            'date_validation' => null,
            'validated_by_agent_id' => null,
            'periodicite' => $periodicite,
            'date_debut_remboursement' => null,
            'frais_adhesion' => 0,
            'frais_dossier' => 0,
            'taux_penalite' => 2.5,
            'mode_penalite' => 'pourcentage',
            'etat' => 'soumis',
        ];
    }
}
