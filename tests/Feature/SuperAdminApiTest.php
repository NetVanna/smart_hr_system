<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'Super Admin']);
    }

    public function test_can_get_stats()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_companies',
                    'total_employees',
                    'active_subscriptions',
                    'active_alerts',
                    'system_status'
                ]
            ]);
    }

    public function test_can_approve_subscription()
    {
        $company = Company::factory()->create();
        $subscription = Subscription::create([
            'company_id' => $company->id,
            'plan' => 'Business',
            'price' => 39.00,
            'status' => 'Pending Approval'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/subscriptions/{$subscription->id}/approve");

        $response->assertStatus(200);
        $this->assertEquals('Active', $subscription->fresh()->status);
        $this->assertEquals('Active', $company->fresh()->subscription_status);
    }

    public function test_can_update_company_status()
    {
        $company = Company::factory()->create(['subscription_status' => 'Active']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/admin/companies/{$company->id}/status", ['status' => 'Suspended']);

        $response->assertStatus(200);
        $this->assertEquals('Suspended', $company->fresh()->subscription_status);
    }
}
