<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        // For employees, show only their leaves; for HR/Admins, show all
        if (auth()->user()->role === 'Employee') {
            $employee_id = auth()->user()->employee_id;
            $leaves = \App\Models\Leave::whereHas('employee', function($q) use($employee_id) {
                $q->where('employee_id', $employee_id);
            })->with('employee')->orderBy('created_at', 'desc')->get();
        } else {
            $leaves = \App\Models\Leave::with('employee')->orderBy('created_at', 'desc')->get();
        }
        
        return view('leaves.index', compact('leaves'));
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
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string'
        ]);

        $employee = \App\Models\Employee::where('employee_id', auth()->user()->employee_id)->first();

        if (!$employee) {
            return back()->with('error', 'You must be linked to an employee profile to apply for leave.');
        }

        \App\Models\Leave::create([
            'employee_id' => $employee->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    public function edit(\App\Models\Leave $leave)
    {
        return view('leaves.edit', compact('leave'));
    }

    public function update(Request $request, \App\Models\Leave $leave)
    {
        // Only HR or Admin can change status
        if (in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager'])) {
            $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected'
            ]);
            $leave->update(['status' => $request->status]);
            return redirect()->route('leaves.index')->with('success', 'Leave status updated.');
        }

        return redirect()->route('leaves.index')->with('error', 'Unauthorized to change leave status.');
    }

    public function destroy(\App\Models\Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave request deleted.');
    }
}
