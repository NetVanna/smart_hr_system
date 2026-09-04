<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Leave;
use App\Models\Ticket;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = request()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'Super Admin') {
            return redirect()->route('superadmin.dashboard');
        }

        $companyId = $user->company_id;
        $company = $user->company;

        $stats = [
            'totalEmployees'   => Employee::where('company_id', $companyId)->count(),
            'presentToday'     => Attendance::where('company_id', $companyId)
                                    ->whereDate('date', today())->count(),
            'onLeave'          => Leave::where('company_id', $companyId)
                                    ->where('status', 'Approved')
                                    ->whereDate('start_date', '<=', today())
                                    ->whereDate('end_date', '>=', today())->count(),
            'totalDepartments' => Department::where('company_id', $companyId)->count(),
            'totalBranches'    => Branch::where('company_id', $companyId)->count(),
            'pendingLeaves'    => Leave::where('company_id', $companyId)
                                    ->where('status', 'Pending')->count(),
            'pendingExpenses'  => Expense::where('company_id', $companyId)
                                    ->where('status', 'Pending')->count(),
            'openTickets'      => Ticket::where('company_id', $companyId)
                                    ->whereIn('status', ['Open', 'In Progress'])->count(),
            'totalUsers'       => User::where('company_id', $companyId)->count(),
        ];

        // Executive Financial & Subscription Metrics for Company Admin
        $exchangeRate = (float)($company->exchange_rate ?? 4100);
        $monthlyPayrollUSD = (float)Employee::where('company_id', $companyId)
            ->where('status', 'Active')
            ->sum('salary');
        $monthlyPayrollKHR = $monthlyPayrollUSD * $exchangeRate;

        $planLimits = [
            'Basic'      => 10,
            'Pro'        => 50,
            'Growth'     => 50,
            'Enterprise' => 500,
        ];
        $currentPlan = $company->subscription_plan ?? 'Basic';
        $maxEmployees = $planLimits[$currentPlan] ?? 25;
        $employeeCount = $stats['totalEmployees'];
        $quotaUsagePercent = min(100, round(($employeeCount / max(1, $maxEmployees)) * 100));

        $adminMetrics = [
            'monthlyPayrollUSD'  => $monthlyPayrollUSD,
            'monthlyPayrollKHR'  => $monthlyPayrollKHR,
            'exchangeRate'       => $exchangeRate,
            'planName'           => $currentPlan,
            'planStatus'         => $company->subscription_status ?? 'Trial',
            'maxEmployees'       => $maxEmployees,
            'quotaUsagePercent'  => $quotaUsagePercent,
            'latestSubscription' => $company ? $company->subscriptions()->latest()->first() : null,
        ];

        $recentAttendances = Attendance::with('employee')
            ->where('company_id', $companyId)
            ->whereDate('date', today())
            ->latest()
            ->take(5)
            ->get();

        $recentLeaves = Leave::with('employee')
            ->where('company_id', $companyId)
            ->latest()
            ->take(5)
            ->get();

        // HR Manager Operational Queue Data
        $pendingLeavesQueue = Leave::with('employee')
            ->where('company_id', $companyId)
            ->where('status', 'Pending')
            ->latest()
            ->take(5)
            ->get();

        $attendanceStats = [
            'checkedIn'  => Attendance::where('company_id', $companyId)->whereDate('date', today())->whereNotNull('check_in')->count(),
            'checkedOut' => Attendance::where('company_id', $companyId)->whereDate('date', today())->whereNotNull('check_out')->count(),
        ];

        $activeShiftsCount = \App\Models\Shift::where('company_id', $companyId)->count();
        $monthPayrollCount = \App\Models\Payroll::where('company_id', $companyId)
            ->where('month', now()->format('F'))
            ->where('year', now()->year)
            ->count();

        return view('dashboard.index', compact('stats', 'adminMetrics', 'recentAttendances', 'recentLeaves', 'pendingLeavesQueue', 'attendanceStats', 'activeShiftsCount', 'monthPayrollCount'));
    }
}
