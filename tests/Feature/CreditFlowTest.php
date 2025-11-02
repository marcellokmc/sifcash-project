<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Adherent;
use App\Models\Credit;

class CreditFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_simplified_credit_flow_application_then_approval_with_contract_generated(): void
    {
        // 1) Create an adherent with account
$adherent = \Database\Factories\AdherentFactory::new()->create();
        $adherentUser = $adherent->user;

        // 2) Adherent submits a simplified credit application (form submit)
        $payload = [
            'montant' => 150000,
            'duree' => 12,
            'motif' => 'Achat matériels',
            'garanties' => 'Caution solidaire',
        ];

        $this->actingAs($adherentUser)
            ->post('/adherent/credits', $payload)
            ->assertRedirect();

        $credit = Credit::first();
        $this->assertNotNull($credit);
        $this->assertEquals('soumis', $credit->etat);

        // 3) Admin approves credit (should auto-generate contract and notify)
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->post("/admin/credits/{$credit->id}/approve", [
                'montant_accorde' => 150000,
                'taux' => 12.00,
            ])->assertRedirect();

        $credit->refresh();
        $this->assertEquals('approuve', $credit->etat);
        $this->assertNotEmpty($credit->contract_path);
    }
}
