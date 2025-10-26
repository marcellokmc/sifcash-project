<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Audit;
use Carbon\Carbon;

class AuditFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_audits_by_user_action_and_date_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $other = User::factory()->create(['role' => 'agent']);

        // Seed some audits
        Audit::create([
            'user_id' => $admin->id,
            'action' => 'admin.credits.approve',
            'model_type' => 'App\\Models\\Credit',
            'model_id' => 1,
            'ancienne_valeur' => null,
            'nouvelle_valeur' => ['status' => 200],
            'ip' => '127.0.0.1',
            'created_at' => Carbon::parse('2025-10-05 10:00:00'),
        ]);
        Audit::create([
            'user_id' => $admin->id,
            'action' => 'admin.credits.reject',
            'model_type' => 'App\\Models\\Credit',
            'model_id' => 2,
            'ancienne_valeur' => null,
            'nouvelle_valeur' => ['status' => 200],
            'ip' => '127.0.0.1',
            'created_at' => Carbon::parse('2025-10-10 11:00:00'),
        ]);
        Audit::create([
            'user_id' => $other->id,
            'action' => 'admin.credits.contract',
            'model_type' => 'App\\Models\\Credit',
            'model_id' => 3,
            'ancienne_valeur' => null,
            'nouvelle_valeur' => ['status' => 200],
            'ip' => '127.0.0.1',
            'created_at' => Carbon::parse('2025-10-15 12:00:00'),
        ]);

        // Filter by user_id and action within date range
        $response = $this->actingAs($admin)
            ->get('/admin/audits?user_id='.$admin->id.'&action=admin.credits.approve&date_from=2025-10-01&date_to=2025-10-09')
            ->assertOk()
            ->json('data');

        $this->assertIsArray($response);
        // Expect only the first audit to match
        $this->assertCount(1, $response);
        $this->assertEquals('admin.credits.approve', $response[0]['action']);
        $this->assertEquals($admin->id, $response[0]['user_id']);

        // Filter by another action
        $response2 = $this->actingAs($admin)
            ->get('/admin/audits?action=admin.credits.reject')
            ->assertOk()
            ->json('data');
        $this->assertGreaterThanOrEqual(1, count($response2));
        $this->assertEquals('admin.credits.reject', $response2[0]['action']);
    }
}
