<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyAdminFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $companyAdmin;
    private User $hrManager;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Tenant Company
        $this->company = Company::create([
            'name'                => 'Angkor Tech Solutions',
            'email'               => 'info@angkortech.kh',
            'base_currency'       => 'USD',
            'exchange_rate'       => 4100.00,
            'subscription_plan'   => 'Growth',
            'subscription_status' => 'Active',
            'onboarding_step'     => 'completed',
        ]);

        // Create Company Admin (Tenant Owner)
        $this->companyAdmin = User::factory()->create([
            'company_id'        => $this->company->id,
            'role'              => 'Company Admin',
            'name'              => 'Chan Dara',
            'email'             => 'dara@angkortech.kh',
            'phone'             => '012999888',
            'telegram_username' => 'dara_angkor',
        ]);

        // Create HR Manager
        $this->hrManager = User::factory()->create([
            'company_id'        => $this->company->id,
            'role'              => 'HR Manager',
            'name'              => 'Srey Leak',
            'email'             => 'leak@angkortech.kh',
            'phone'             => '098111222',
        ]);
    }

    public function test_company_admin_can_view_and_invite_team_users(): void
    {
        $response = $this->actingAs($this->companyAdmin)
            ->get(route('company.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Chan Dara');
        $response->assertSee('Srey Leak');

        // Invite a new HR Manager
        $postData = [
            'name'                  => 'Bopha Vong',
            'email'                 => 'bopha@angkortech.kh',
            'phone'                 => '077333444',
            'telegram_username'     => 'bopha_hr',
            'role'                  => 'HR Manager',
            'password'              => 'Secret123!',
            'password_confirmation' => 'Secret123!',
        ];

        $inviteResponse = $this->actingAs($this->companyAdmin)
            ->post(route('company.users.store'), $postData);

        $inviteResponse->assertRedirect(route('company.users.index'));

        $this->assertDatabaseHas('users', [
            'company_id'        => $this->company->id,
            'email'             => 'bopha@angkortech.kh',
            'role'              => 'HR Manager',
            'telegram_username' => 'bopha_hr',
        ]);
    }

    public function test_company_admin_can_update_user_role_and_details(): void
    {
        $updateData = [
            'name'              => 'Srey Leak (Senior HR)',
            'email'             => 'leak@angkortech.kh',
            'phone'             => '098111222',
            'telegram_username' => 'leak_senior',
            'role'              => 'Company Admin', // Promoted to Admin
        ];

        $response = $this->actingAs($this->companyAdmin)
            ->put(route('company.users.update', $this->hrManager), $updateData);

        $response->assertRedirect(route('company.users.index'));

        $this->assertDatabaseHas('users', [
            'id'                => $this->hrManager->id,
            'name'              => 'Srey Leak (Senior HR)',
            'role'              => 'Company Admin',
            'telegram_username' => 'leak_senior',
        ]);
    }

    public function test_cannot_delete_only_company_admin_or_self(): void
    {
        // Try deleting self
        $responseSelf = $this->actingAs($this->companyAdmin)
            ->delete(route('company.users.destroy', $this->companyAdmin));

        $responseSelf->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->companyAdmin->id]);

        // Try deleting HR Manager (should succeed)
        $responseHR = $this->actingAs($this->companyAdmin)
            ->delete(route('company.users.destroy', $this->hrManager));

        $responseHR->assertRedirect(route('company.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $this->hrManager->id]);
    }

    public function test_company_admin_can_update_cambodia_company_settings(): void
    {
        $response = $this->actingAs($this->companyAdmin)
            ->put(route('settings.company.update'), [
                'name'             => 'Angkor Tech Global Solutions',
                'email'            => 'contact@angkortech.kh',
                'phone'            => '023888777',
                'address'          => 'Preah Sihanouk Blvd, Phnom Penh',
                'base_currency'    => 'USD',
                'exchange_rate'    => 4120.00,
                'geofence_radius'  => 120,
                'latitude'         => 11.5564,
                'longitude'        => 104.9282,
                'telegram_chat_id' => '-100987654321',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'id'               => $this->company->id,
            'name'             => 'Angkor Tech Global Solutions',
            'exchange_rate'    => 4120.00,
            'telegram_chat_id' => '-100987654321',
        ]);
    }

    public function test_company_admin_dashboard_shows_payroll_and_subscription_metrics(): void
    {
        // Add active employees with salaries
        Employee::create([
            'company_id'  => $this->company->id,
            'employee_id' => 'EMP-001',
            'first_name'  => 'Visal',
            'last_name'   => 'Sok',
            'salary'      => 800.00,
            'status'      => 'Active',
        ]);

        Employee::create([
            'company_id'  => $this->company->id,
            'employee_id' => 'EMP-002',
            'first_name'  => 'Kolab',
            'last_name'   => 'Chea',
            'salary'      => 600.00,
            'status'      => 'Active',
        ]);

        Branch::create([
            'company_id' => $this->company->id,
            'name'       => 'Siem Reap Branch',
            'code'       => 'REP-01',
        ]);

        $response = $this->actingAs($this->companyAdmin)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        // Total salary = $1,400.00
        $response->assertSee('1,400.00');
        $response->assertSee('1 Branches');
    }

    public function test_company_admin_can_view_billing_and_submit_khqr_proof(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->companyAdmin)
            ->get(route('company.billing.index'));

        $response->assertStatus(200);
        $response->assertSee('Subscription & Billing');
        $response->assertSee('Growth');

        $receipt = UploadedFile::fake()->image('bakong_slip.png');

        $postResponse = $this->actingAs($this->companyAdmin)
            ->post(route('company.billing.submitProof'), [
                'plan'    => 'Enterprise',
                'receipt' => $receipt,
            ]);

        $postResponse->assertRedirect();
        $this->assertDatabaseHas('subscriptions', [
            'company_id' => $this->company->id,
            'plan'       => 'Enterprise',
            'price'      => 149.00,
            'status'     => 'Pending Approval',
        ]);
    }

    public function test_hr_manager_cannot_manage_company_users_or_billing(): void
    {
        // HR Manager navigating to user management should be forbidden
        $responseUsers = $this->actingAs($this->hrManager)
            ->get(route('company.users.index'));

        $responseUsers->assertRedirect(route('dashboard'));

        // HR Manager navigating to billing should be forbidden
        $responseBilling = $this->actingAs($this->hrManager)
            ->get(route('company.billing.index'));

        $responseBilling->assertRedirect(route('dashboard'));
    }
}
