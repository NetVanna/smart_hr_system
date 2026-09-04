<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoCompanySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['email' => 'contact@attendee.demo'],
            [
                'name' => 'HR Attendee Corp',
                'phone' => '+1 (555) 019-2834',
                'address' => '3517 W. Gray St. Utica, Pennsylvania',
                'subscription_plan' => 'Enterprise',
                'subscription_status' => 'Active',
                'onboarding_step' => 'completed',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Michael Mitc',
                'password' => Hash::make('password123'),
                'role' => 'Company Admin',
                'company_id' => $company->id,
            ]
        );

        $designDept = Department::firstOrCreate(
            ['company_id' => $company->id, 'department_name' => 'Product & Design'],
            ['description' => 'UI/UX Design and Product Team']
        );

        $engDept = Department::firstOrCreate(
            ['company_id' => $company->id, 'department_name' => 'Engineering'],
            ['description' => 'Software Engineering & Cloud Ops']
        );

        $emp1 = Employee::firstOrCreate(
            ['company_id' => $company->id, 'email' => 'michael.mitc@example.com'],
            [
                'first_name' => 'Michael',
                'last_name' => 'Mitc',
                'employee_id' => 'EMP-787998',
                'phone' => '(603) 555-0123',
                'department_id' => $designDept->id,
                'position' => 'Lead UI/UX Designer',
                'joining_date' => Carbon::now()->subYears(2),
                'salary' => 6500,
                'status' => 'Active',
            ]
        );

        $emp2 = Employee::firstOrCreate(
            ['company_id' => $company->id, 'email' => 'jane.hawkins@demo.com'],
            [
                'first_name' => 'Jane',
                'last_name' => 'Hawkins',
                'employee_id' => 'EMP-882194',
                'phone' => '(555) 349-9281',
                'department_id' => $designDept->id,
                'position' => 'Product Designer',
                'joining_date' => Carbon::now()->subYear(),
                'salary' => 5200,
                'status' => 'Active',
            ]
        );

        $emp3 = Employee::firstOrCreate(
            ['company_id' => $company->id, 'email' => 'leslie.alexander@demo.com'],
            [
                'first_name' => 'Leslie',
                'last_name' => 'Alexander',
                'employee_id' => 'EMP-904128',
                'phone' => '(555) 782-1920',
                'department_id' => $engDept->id,
                'position' => 'Senior Frontend Dev',
                'joining_date' => Carbon::now()->subMonths(18),
                'salary' => 5800,
                'status' => 'Active',
            ]
        );

        $emp4 = Employee::firstOrCreate(
            ['company_id' => $company->id, 'email' => 'jenny.wilson@demo.com'],
            [
                'first_name' => 'Jenny',
                'last_name' => 'Wilson',
                'employee_id' => 'EMP-612984',
                'phone' => '(555) 902-3847',
                'department_id' => $engDept->id,
                'position' => 'Backend Architect',
                'joining_date' => Carbon::now()->subMonths(8),
                'salary' => 6200,
                'status' => 'Active',
            ]
        );

        // Seed some leaves
        Leave::firstOrCreate(
            ['company_id' => $company->id, 'employee_id' => $emp2->id, 'start_date' => Carbon::now()->addDays(5)->toDateString()],
            [
                'end_date' => Carbon::now()->addDays(8)->toDateString(),
                'leave_type' => 'Medical Leave',
                'reason' => 'Recovery following minor medical procedure.',
                'status' => 'Approved',
            ]
        );

        Leave::firstOrCreate(
            ['company_id' => $company->id, 'employee_id' => $emp3->id, 'start_date' => Carbon::now()->addDays(2)->toDateString()],
            [
                'end_date' => Carbon::now()->addDays(4)->toDateString(),
                'leave_type' => 'Planned Leave',
                'reason' => 'Family event attendance.',
                'status' => 'Pending',
            ]
        );

        Leave::firstOrCreate(
            ['company_id' => $company->id, 'employee_id' => $emp4->id, 'start_date' => Carbon::now()->subDays(10)->toDateString()],
            [
                'end_date' => Carbon::now()->subDays(8)->toDateString(),
                'leave_type' => 'Holiday Leave',
                'reason' => 'Short trip.',
                'status' => 'Rejected',
            ]
        );

        // Seed some attendances
        Attendance::firstOrCreate(
            ['company_id' => $company->id, 'employee_id' => $emp1->id, 'date' => Carbon::today()->toDateString()],
            [
                'check_in' => '09:15:00',
                'check_out' => null,
                'method' => 'QR',
            ]
        );

        Attendance::firstOrCreate(
            ['company_id' => $company->id, 'employee_id' => $emp2->id, 'date' => Carbon::today()->toDateString()],
            [
                'check_in' => '08:55:00',
                'check_out' => null,
                'method' => 'QR',
            ]
        );
    }
}
