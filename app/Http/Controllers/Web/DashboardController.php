<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Ticket;

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

        $stats = [
            'totalEmployees'   => Employee::where('company_id', $companyId)->count(),
            'presentToday'     => Attendance::where('company_id', $companyId)
                                    ->whereDate('check_in', today())->count(),
            'onLeave'          => Employee::where('company_id', $companyId)
                                    ->where('status', 'On Leave')->count(),
            'totalDepartments' => \App\Models\Department::where('company_id', $companyId)->count(),
            'pendingLeaves'    => Leave::where('company_id', $companyId)
                                    ->where('status', 'Pending')->count(),
            'openTickets'      => Ticket::where('company_id', $companyId)
                                    ->whereIn('status', ['Open', 'In Progress'])->count(),
        ];

        return view('dashboard.index', compact('stats'));
    }
}
