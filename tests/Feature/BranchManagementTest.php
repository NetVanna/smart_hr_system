<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BranchManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $company;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $unique = time() . rand(100, 999);
        $this->company = Company::create([
            'name'                => 'Branch Test Co ' . $unique,
            'email'               => 'branch_co_' . $unique . '@demo.com',
            'subscription_plan'   => '14-Day Free Trial',
            'subscription_status' => 'Active',
            'latitude'            => 11.5564,
            'longitude'           => 104.9282,
            'geofence_radius'     => 100,
        ]);

        $this->admin = User::create([
            'company_id'  => $this->company->id,
            'name'        => 'Branch Admin',
            'email'       => 'admin_' . $unique . '@demo.com',
            'phone'       => '012' . rand(100000, 999999),
            'password'    => Hash::make('secret123'),
            'role'        => 'Company Admin',
        ]);
    }

    public function test_company_admin_can_view_branches_list(): void
    {
        Branch::create([
            'company_id' => $this->company->id,
            'name'       => 'Phnom Penh HQ',
            'code'       => 'PP-01',
            'address'    => 'St 271, Phnom Penh',
            'latitude'   => 11.5564,
            'longitude'  => 104.9282,
        ]);

        $response = $this->actingAs($this->admin)->get(route('branches.index'));
        $response->assertStatus(200);
        $response->assertSee('Phnom Penh HQ');
        $response->assertSee('PP-01');
    }

    public function test_company_admin_can_create_branch_with_gps_coordinates(): void
    {
        $response = $this->actingAs($this->admin)->post(route('branches.store'), [
            'name'            => 'Siem Reap Branch',
            'code'            => 'SR-01',
            'phone'           => '063999888',
            'address'         => 'Pub Street, Siem Reap, Cambodia',
            'latitude'        => 13.3633,
            'longitude'       => 103.8564,
            'geofence_radius' => 150,
            'is_active'       => 1,
        ]);

        $response->assertRedirect(route('branches.index'));
        $response->assertSessionHas('success');

        $branch = Branch::where('company_id', $this->company->id)->where('code', 'SR-01')->first();
        $this->assertNotNull($branch);
        $this->assertEquals('Siem Reap Branch', $branch->name);
        $this->assertEquals(13.3633, $branch->latitude);
        $this->assertEquals(103.8564, $branch->longitude);
        $this->assertEquals(150, $branch->geofence_radius);
    }

    public function test_company_admin_can_update_branch(): void
    {
        $branch = Branch::create([
            'company_id' => $this->company->id,
            'name'       => 'Old Branch Name',
            'code'       => 'OLD-01',
            'latitude'   => 11.5500,
            'longitude'  => 104.9000,
        ]);

        $response = $this->actingAs($this->admin)->put(route('branches.update', $branch), [
            'name'            => 'Battambang Regional Hub',
            'code'            => 'BB-01',
            'phone'           => '053123456',
            'address'         => 'Battambang City, Cambodia',
            'latitude'        => 13.0957,
            'longitude'       => 103.2022,
            'geofence_radius' => 200,
            'is_active'       => 1,
        ]);

        $response->assertRedirect(route('branches.index'));
        $branch->refresh();
        $this->assertEquals('Battambang Regional Hub', $branch->name);
        $this->assertEquals('BB-01', $branch->code);
        $this->assertEquals(13.0957, $branch->latitude);
    }

    public function test_company_admin_can_delete_branch_and_unassign_employees(): void
    {
        $branch = Branch::create([
            'company_id' => $this->company->id,
            'name'       => 'To Be Deleted',
            'code'       => 'DEL-01',
        ]);

        $employee = Employee::create([
            'company_id'  => $this->company->id,
            'branch_id'   => $branch->id,
            'employee_id' => 'EMP-' . rand(1000, 9999),
            'first_name'  => 'Test',
            'last_name'   => 'Staff',
            'status'      => 'Active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('branches.destroy', $branch));
        $response->assertRedirect(route('branches.index'));

        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);

        $employee->refresh();
        $this->assertNull($employee->branch_id);
    }

    public function test_multi_tenant_isolation_prevents_seeing_other_companies_branches(): void
    {
        // Another company
        $unique2 = time() . rand(100, 999);
        $otherCompany = Company::create([
            'name'                => 'Other Co ' . $unique2,
            'email'               => 'other_' . $unique2 . '@demo.com',
            'subscription_plan'   => '14-Day Free Trial',
            'subscription_status' => 'Active',
        ]);

        $otherBranch = Branch::create([
            'company_id' => $otherCompany->id,
            'name'       => 'Secret Competitor Branch',
            'code'       => 'SEC-99',
        ]);

        // Current admin should NOT see the other company's branch
        $response = $this->actingAs($this->admin)->get(route('branches.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Secret Competitor Branch');
    }

    public function test_employee_can_be_assigned_to_branch(): void
    {
        $branch = Branch::create([
            'company_id' => $this->company->id,
            'name'       => 'Kampong Cham Hub',
            'code'       => 'KC-01',
        ]);

        $response = $this->actingAs($this->admin)->post(route('employees.store'), [
            'employee_id' => 'EMP-KC-001',
            'branch_id'   => $branch->id,
            'first_name'  => 'Chan',
            'last_name'   => 'Dara',
            'email'       => 'dara@test.com',
            'position'    => 'Branch Manager',
            'joining_date'=> '2026-01-01',
            'salary'      => 800,
        ]);

        $response->assertRedirect(route('employees.index'));

        $employee = Employee::where('employee_id', 'EMP-KC-001')->first();
        $this->assertNotNull($employee);
        $this->assertEquals($branch->id, $employee->branch_id);
    }
}
