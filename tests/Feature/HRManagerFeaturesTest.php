<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HRManagerFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private User $hrManager;
    private Company $company;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name'                => 'Test Company KH',
            'email'               => 'test@company.kh',
            'subscription_plan'   => 'Pro',
            'subscription_status' => 'Active',
            'exchange_rate'       => 4100,
        ]);

        $this->hrManager = User::create([
            'name'       => 'HR Manager User',
            'email'      => 'hr@company.kh',
            'password'   => bcrypt('password'),
            'role'       => 'HR Manager',
            'company_id' => $this->company->id,
        ]);

        $this->employee = Employee::create([
            'company_id'  => $this->company->id,
            'employee_id' => 'EMP001',
            'first_name'  => 'Sophal',
            'last_name'   => 'Chan',
            'position'    => 'Developer',
            'status'      => 'Active',
            'salary'      => 600.00,
            'hire_date'   => now()->subYear(),
        ]);
    }

    /** @test */
    public function test_hr_manager_dashboard_shows_operational_queues()
    {
        $response = $this->actingAs($this->hrManager)->get(route('dashboard'));

        $response->assertStatus(200);
        // The HR Manager toolbar should be visible
        $response->assertSee('HR Manager Operations');
        $response->assertSee('Checked-In Today');
        $response->assertSee('Pending Leaves');
        $response->assertSee('Work Shifts');
        $response->assertSee('Payslips This Month');
    }

    /** @test */
    public function test_hr_manager_can_quick_approve_pending_leave()
    {
        $leave = Leave::create([
            'company_id'  => $this->company->id,
            'employee_id' => $this->employee->id,
            'leave_type'  => 'Annual Leave',
            'start_date'  => now()->addDays(3),
            'end_date'    => now()->addDays(5),
            'reason'      => 'Family trip',
            'status'      => 'Pending',
        ]);

        $response = $this->actingAs($this->hrManager)
            ->post(route('leaves.quickApprove', $leave));

        $response->assertRedirect();
        $this->assertDatabaseHas('leaves', [
            'id'     => $leave->id,
            'status' => 'Approved',
        ]);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function test_hr_manager_can_quick_reject_pending_leave()
    {
        $leave = Leave::create([
            'company_id'  => $this->company->id,
            'employee_id' => $this->employee->id,
            'leave_type'  => 'Sick Leave',
            'start_date'  => now()->addDay(),
            'end_date'    => now()->addDays(2),
            'reason'      => 'Feeling unwell',
            'status'      => 'Pending',
        ]);

        $response = $this->actingAs($this->hrManager)
            ->post(route('leaves.quickReject', $leave));

        $response->assertRedirect();
        $this->assertDatabaseHas('leaves', [
            'id'     => $leave->id,
            'status' => 'Rejected',
        ]);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function test_hr_manager_can_monitor_daily_attendance()
    {
        Attendance::create([
            'company_id'  => $this->company->id,
            'employee_id' => $this->employee->id,
            'date'        => today(),
            'check_in'    => now()->setHour(8)->setMinute(55),
            'method'      => 'Mobile App',
        ]);

        $response = $this->actingAs($this->hrManager)->get(route('shifts.monitor'));
        $response->assertStatus(200);
        $response->assertSee('Live Attendance Monitor');
        $response->assertSee('Sophal');
    }

    /** @test */
    public function test_hr_manager_can_view_shift_roster()
    {
        Shift::create([
            'company_id' => $this->company->id,
            'name'       => 'Morning Shift',
            'start_time' => '08:00',
            'end_time'   => '17:00',
        ]);

        $response = $this->actingAs($this->hrManager)->get(route('shifts.index'));
        $response->assertStatus(200);
        $response->assertSee('Morning Shift');
    }

    /** @test */
    public function test_hr_manager_can_access_payroll_create_with_exchange_rate()
    {
        $response = $this->actingAs($this->hrManager)->get(route('payrolls.create'));
        $response->assertStatus(200);
        $response->assertSee('4,100'); // exchange rate formatted
        $response->assertSee('KHR');
        $response->assertSee('Auto-calculate NSSF');
    }

    /** @test */
    public function test_hr_manager_can_generate_payroll()
    {
        $response = $this->actingAs($this->hrManager)->post(route('payrolls.store'), [
            'employee_id'  => $this->employee->id,
            'basic_salary' => 600,
            'allowance'    => 50,
            'deductions'   => 31.80,
            'month'        => 'September',
            'year'         => 2026,
        ]);

        $response->assertRedirect(route('payrolls.index'));
        $this->assertDatabaseHas('payrolls', [
            'company_id'   => $this->company->id,
            'employee_id'  => $this->employee->id,
            'basic_salary' => 600,
            'month'        => 'September',
        ]);
    }

    /** @test */
    public function test_hr_manager_cannot_access_company_admin_settings()
    {
        $response = $this->actingAs($this->hrManager)->get(route('settings.company.edit'));
        // Should be forbidden or redirect (not 200 with full page)
        $this->assertNotEquals(200, $response->getStatusCode());
    }
}
