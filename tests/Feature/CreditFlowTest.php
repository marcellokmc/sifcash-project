<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Adherent;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreditFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_credit_workflow_from_application_to_payment(): void
    {
        // 1) Create an adherent with account
        $adherent = \Database\Factories\AdherentFactory::new()->create();
        $adherentUser = $adherent->user;

        // 2) Adherent submits a credit application
        $payload = [
            'montant_demande' => 150000,
            'duree' => 12,
            'taux' => 12.00,
            'type_credit' => 'personnel',
            'periodicite' => 'mensuel',
            'frais_adhesion' => 0,
            'frais_dossier' => 0,
        ];

        $this->actingAs($adherentUser)
            ->post('/adherent/credits', $payload)
            ->assertCreated()
            ->assertJsonStructure(['message','credit_id']);

        $credit = Credit::first();
        $this->assertNotNull($credit);
        $this->assertEquals('soumis', $credit->etat);

        // 3) Admin approves credit
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->post("/admin/credits/{$credit->id}/approve", [
                'montant_accorde' => 150000,
                'taux' => 12.00,
            ])->assertOk();

        $credit->refresh();
        $this->assertEquals('approuve', $credit->etat);

        // 4) Admin sets contract with start date
        $start = Carbon::now()->addDays(3)->toDateString();
        $this->post("/admin/credits/{$credit->id}/contract", [
            'date_debut_remboursement' => $start,
        ])->assertOk();

        $credit->refresh();
        $this->assertEquals('contrat', $credit->etat);

        // 5) Admin generates schedule
        $this->post("/admin/credits/{$credit->id}/generate-schedule")
            ->assertOk();

        $credit->refresh();
        $this->assertEquals('actif', $credit->etat);
        $this->assertGreaterThan(0, $credit->echeances()->count());

        // 6) Admin records a payment on first installment
        $echeance = $credit->echeances()->orderBy('date_echeance')->first();
        $this->assertNotNull($echeance);

        $this->post("/admin/credits/{$credit->id}/record-payment", [
            'echeance_id' => $echeance->id,
            'date_paiement' => Carbon::parse($echeance->date_echeance)->toDateString(),
            'montant' => (float)$echeance->montant_attendu,
            'mode' => 'espece',
            'reference' => 'TEST-'.Str::random(6),
        ])->assertOk();

        $echeance->refresh();
        $this->assertEquals('payé', $echeance->statut);
        $this->assertTrue(((float)$echeance->montant_paye) >= (float)$echeance->montant_attendu);
    }
}
