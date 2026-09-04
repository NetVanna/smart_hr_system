<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // For employees, show only their leaves; for HR/Admins, show company leaves
        if ($user->role === 'Employee') {
            $employeeId = $user->employee_id;
            $allLeaves = Leave::whereHas('employee', function($q) use($employeeId) {
                $q->where('employee_id', $employeeId);
            })->with('employee')->latest()->get();
        } else {
            $allLeaves = Leave::with('employee')->latest()->get();
        }
        
        $leaveStats = [
            'total'    => $allLeaves->count(),
            'approved' => $allLeaves->where('status', 'Approved')->count(),
            'pending'  => $allLeaves->where('status', 'Pending')->count(),
            'rejected' => $allLeaves->where('status', 'Rejected')->count(),
        ];

        // Status filter
        $leaves = $allLeaves;
        if ($request->filled('status') && in_array($request->status, ['Pending', 'Approved', 'Rejected'])) {
            $leaves = $allLeaves->where('status', $request->status);
        }

        return view('leaves.index', compact('leaves', 'leaveStats', 'allLeaves'));
    }

    public function create()
    {
        return view('leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string'
        ]);

        $employee = Employee::where('employee_id', auth()->user()->employee_id)->first();

        if (!$employee) {
            return back()->with('error', 'You must be linked to an employee profile to apply for leave.');
        }

        Leave::create([
            'company_id'  => auth()->user()->company_id,
            'employee_id' => $employee->id,
            'leave_type'  => $request->leave_type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'reason'      => $request->reason,
            'status'      => 'Pending'
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    public function edit(Leave $leave)
    {
        return view('leaves.edit', compact('leave'));
    }

    public function update(Request $request, Leave $leave)
    {
        // Only HR or Admin can change status
        if (in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager'])) {
            $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected'
            ]);
            $leave->update(['status' => $request->status]);
            return redirect()->route('leaves.index')->with('success', "Leave status updated to {$request->status}.");
        }

        return redirect()->route('leaves.index')->with('error', 'Unauthorized to change leave status.');
    }

    /**
     * 1-Click Fast Approval for HR Manager and Admins
     */
    public function quickApprove(Leave $leave)
    {
        if (!in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager'])) {
            return back()->with('error', 'Unauthorized to approve leaves.');
        }

        $leave->update(['status' => 'Approved']);

        $empName = $leave->employee ? "{$leave->employee->first_name} {$leave->employee->last_name}" : 'Employee';

        return back()->with('success', "✅ Leave for {$empName} has been APPROVED.");
    }

    /**
     * 1-Click Fast Rejection for HR Manager and Admins
     */
    public function quickReject(Leave $leave)
    {
        if (!in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager'])) {
            return back()->with('error', 'Unauthorized to reject leaves.');
        }

        $leave->update(['status' => 'Rejected']);

        $empName = $leave->employee ? "{$leave->employee->first_name} {$leave->employee->last_name}" : 'Employee';

        return back()->with('success', "⚠️ Leave for {$empName} has been REJECTED.");
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave request deleted.');
    }
}
