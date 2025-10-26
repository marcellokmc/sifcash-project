<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use App\Models\User;
use Carbon\Carbon;

class PaymentPenaltiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_penalties_percentage_mode_marks_overdue_and_applies_penalty(): void
    {
        // Create staff user to pass route middlewares if needed later
        User::factory()->create(['role' => 'admin']);

        $credit = Credit::create([
            'adherent_id' => \Database\Factories\AdherentFactory::new()->create()->id,
            'montant_demande' => 100000,
            'duree' => 6,
            'taux' => 12.00,
            'type_credit' => 'personnel',
            'statut' => 'en_attente',
            'date_demande' => now(),
            'periodicite' => 'mensuel',
            'etat' => 'actif',
            'taux_penalite' => 2.00, // 2% per period overdue
            'mode_penalite' => 'pourcentage',
        ]);

        // Overdue installment: due 2 months ago, no payment
        $dueDate = Carbon::now()->subMonths(2)->startOfDay();
        $e = EcheanceCredit::create([
            'credit_id' => $credit->id,
            'numero_echeance' => 1,
            'date_echeance' => $dueDate->toDateString(),
            'montant_attendu' => 1000.00,
            'montant_paye' => 0,
            'penalite_appliquee' => 0,
            'statut' => 'en_attente',
        ]);

        // Run penalties command
        $this->artisan('credits:apply-penalties')->assertSuccessful();

        $e->refresh();
        $this->assertEquals('en_retard', $e->statut);
        $this->assertTrue((float)$e->penalite_appliquee > 0.0, 'Penalty should be applied');
    }

    public function test_penalties_fixed_mode_accumulate_per_interval(): void
    {
        $credit = Credit::create([
            'adherent_id' => \Database\Factories\AdherentFactory::new()->create()->id,
            'montant_demande' => 50000,
            'duree' => 10,
            'taux' => 10.00,
            'type_credit' => 'personnel',
            'statut' => 'en_attente',
            'date_demande' => now(),
            'periodicite' => 'hebdomadaire',
            'etat' => 'actif',
            'taux_penalite' => 5.00, // fixed amount per week overdue
            'mode_penalite' => 'fixe',
        ]);

        // Overdue 3 weeks
        $dueDate = Carbon::now()->subWeeks(3)->startOfDay();
        $e = EcheanceCredit::create([
            'credit_id' => $credit->id,
            'numero_echeance' => 1,
            'date_echeance' => $dueDate->toDateString(),
            'montant_attendu' => 2000.00,
            'montant_paye' => 0,
            'penalite_appliquee' => 0,
            'statut' => 'en_attente',
        ]);

        $this->artisan('credits:apply-penalties')->assertSuccessful();

        $e->refresh();
        $this->assertEquals('en_retard', $e->statut);
        $this->assertTrue((float)$e->penalite_appliquee >= 15.00, 'Fixed penalties should accumulate per week');
    }
}
