<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Credit;
use App\Models\Audit;

class AuditMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_is_created_when_approving_credit(): void
    {
        // Arrange: adherent with credit submitted
        $adherent = \Database\Factories\AdherentFactory::new()->create();
        $credit = Credit::create([
            'adherent_id' => $adherent->id,
            'montant_demande' => 100000,
            'duree' => 12,
            'taux' => 12.00,
            'type_credit' => 'personnel',
            'statut' => 'en_attente',
            'date_demande' => now(),
            'periodicite' => 'mensuel',
            'etat' => 'soumis',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        // Act: approve
        $this->actingAs($admin)
            ->post("/admin/credits/{$credit->id}/approve", [
                'montant_accorde' => 100000,
                'taux' => 12.00,
            ])->assertRedirect();

        // Assert: one audit created
        $this->assertTrue(Audit::count() >= 1);
        $audit = Audit::latest('created_at')->first();
        $this->assertEquals('admin.credits.approve', $audit->action);
        $this->assertEquals($admin->id, $audit->user_id);
    }
}
