<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\Branch;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminEnhancementTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private Company $company;
    private User $companyAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Super Admin
        $this->superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'email' => 'superadmin@smarthr.com',
        ]);

        // Create Tenant Company
        $this->company = Company::create([
            'name' => 'Phnom Penh Tech Co',
            'email' => 'contact@pptech.kh',
            'subscription_plan' => '14-Day Free Trial',
            'subscription_status' => 'Trial',
            'onboarding_step' => 'completed',
        ]);

        // Create Company Admin
        $this->companyAdmin = User::factory()->create([
            'company_id' => $this->company->id,
            'role' => 'Company Admin',
            'name' => 'Sok Dara',
            'email' => 'admin@pptech.kh',
            'telegram_username' => 'sok_dara',
        ]);
    }

    public function test_superadmin_dashboard_loads_with_metrics_and_dual_currency(): void
    {
        Subscription::create([
            'company_id' => $this->company->id,
            'plan' => 'Pro Plan',
            'price' => 100.00,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('$100.00');
        $response->assertSee('410,000'); // 100 * 4100 KHR
        $response->assertSee('Cambodia Multi-Tenant SaaS');
    }

    public function test_superadmin_can_impersonate_company_admin_and_return(): void
    {
        // 1. Impersonate
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.companies.impersonate', $this->company));

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($this->companyAdmin->id, auth()->id());
        $this->assertEquals($this->superAdmin->id, session('impersonated_by'));

        // 2. View dashboard with impersonation banner
        $dashResponse = $this->get(route('dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Sok Dara');

        // 3. Navigate directly to superadmin/companies while impersonating -> auto reverts
        $autoRevertResponse = $this->actingAs($this->companyAdmin)
            ->withSession(['impersonated_by' => $this->superAdmin->id])
            ->get(route('superadmin.companies.index'));

        $autoRevertResponse->assertStatus(200);
        $autoRevertResponse->assertSessionMissing('impersonated_by');
        $this->assertEquals($this->superAdmin->id, session(auth()->getName()));

        // 4. Leave impersonation via GET link
        $leaveResponse = $this->actingAs($this->companyAdmin)
            ->withSession(['impersonated_by' => $this->superAdmin->id])
            ->get(route('superadmin.impersonate.leave'));

        $leaveResponse->assertRedirect(route('superadmin.companies.index'));
        $leaveResponse->assertSessionMissing('impersonated_by');
        $this->assertEquals($this->superAdmin->id, session(auth()->getName()));
    }

    public function test_superadmin_can_extend_tenant_trial(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.companies.extend-trial', $this->company), [
                'days' => 30,
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('companies', [
            'id' => $this->company->id,
            'subscription_status' => 'Trial',
        ]);
        $this->assertDatabaseHas('subscriptions', [
            'company_id' => $this->company->id,
            'status' => 'Trial',
        ]);
    }
}
