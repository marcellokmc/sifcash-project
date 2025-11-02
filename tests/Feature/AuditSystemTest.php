<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Audit;
use App\Models\Paiement;
use App\Models\Adherent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AuditSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que les audits sont créés automatiquement lors de la création d'un modèle
     */
    public function test_audit_is_created_when_model_is_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Créer un adhérent (qui utilise le trait Auditable)
        $adherent = Adherent::factory()->create([
            'user_id' => $user->id,
        ]);

        // Vérifier qu'un audit a été créé
        $this->assertDatabaseHas('audits', [
            'user_id' => $user->id,
            'action' => 'created',
            'model_type' => Adherent::class,
            'model_id' => $adherent->id,
            'action_category' => 'adherent',
        ]);
    }

    /**
     * Test que les audits capturent l'utilisateur cible
     */
    public function test_audit_captures_target_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $adherentUser = User::factory()->create();
        $this->actingAs($admin);

        $adherent = Adherent::factory()->create([
            'user_id' => $adherentUser->id,
        ]);

        $audit = Audit::where('model_id', $adherent->id)
            ->where('model_type', Adherent::class)
            ->first();

        // L'utilisateur cible devrait être l'adhérent
        $this->assertEquals($adherentUser->id, $audit->target_user_id);
    }

    /**
     * Test des filtres de la page d'audit
     */
    public function test_audit_index_filters_work()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Créer quelques audits
        Audit::factory()->count(5)->create([
            'action_category' => 'paiement',
        ]);
        Audit::factory()->count(3)->create([
            'action_category' => 'retrait',
        ]);

        // Test du filtre par catégorie
        $response = $this->get(route('admin.audit.index', ['action_category' => 'paiement']));
        $response->assertStatus(200);
        $response->assertSee('paiement');
    }

    /**
     * Test de l'export CSV
     */
    public function test_audit_export_returns_csv()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Audit::factory()->count(10)->create();

        $response = $this->get(route('admin.audit.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /**
     * Test des scopes du modèle Audit
     */
    public function test_audit_scopes_work_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Créer des audits de différentes catégories
        Audit::factory()->create(['action_category' => 'paiement']);
        Audit::factory()->create(['action_category' => 'retrait']);
        Audit::factory()->create(['action_category' => 'adhesion']);
        Audit::factory()->create(['action' => 'validated']);

        // Tester les scopes
        $this->assertEquals(1, Audit::paiements()->count());
        $this->assertEquals(1, Audit::retraits()->count());
        $this->assertEquals(1, Audit::adhesions()->count());
        $this->assertEquals(1, Audit::validations()->count());
    }

    /**
     * Test de l'enregistrement d'une action personnalisée
     */
    public function test_custom_audit_action_can_be_logged()
    {
        $user = User::factory()->create();
        $adherent = Adherent::factory()->create();
        $this->actingAs($user);

        // Enregistrer une action personnalisée
        $adherent->audit('custom_action', 'Test de l\'action personnalisée', $user->id);

        // Vérifier que l'audit a été créé
        $this->assertDatabaseHas('audits', [
            'user_id' => $user->id,
            'action' => 'custom_action',
            'model_id' => $adherent->id,
            'description' => 'Test de l\'action personnalisée',
        ]);
    }

    /**
     * Test que les audits ne sont pas créés sans utilisateur authentifié
     */
    public function test_audit_is_not_created_without_authenticated_user()
    {
        $initialCount = Audit::count();

        // Créer un adhérent sans utilisateur authentifié (comme dans un seeder)
        $adherent = Adherent::factory()->create();

        // Aucun audit ne devrait être créé
        $this->assertEquals($initialCount, Audit::count());
    }

    /**
     * Test de la relation targetUser
     */
    public function test_audit_target_user_relation_works()
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();
        
        $audit = Audit::factory()->create([
            'user_id' => $user->id,
            'target_user_id' => $targetUser->id,
        ]);

        $this->assertInstanceOf(User::class, $audit->targetUser);
        $this->assertEquals($targetUser->id, $audit->targetUser->id);
    }

    /**
     * Test que les statistiques fonctionnent
     */
    public function test_audit_stats_page_is_accessible()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Audit::factory()->count(5)->create();

        $response = $this->get(route('admin.audit.stats'));
        $response->assertStatus(200);
    }
}
