<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->employee_id) {
            return response()->json(['message' => 'User not linked to an employee profile'], 400);
        }

        $employee = Employee::where('employee_id', $user->employee_id)->first();
        if (!$employee) {
            return response()->json(['message' => 'Employee profile not found'], 404);
        }

        $leaves = Leave::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $leaves]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->employee_id) {
            return response()->json(['message' => 'User not linked to an employee profile'], 400);
        }

        $employee = Employee::where('employee_id', $user->employee_id)->first();
        if (!$employee) {
            return response()->json(['message' => 'Employee profile not found'], 404);
        }

        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'attachment' => 'nullable|image|max:2048'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leaves', 'public');
        }

        $leave = Leave::create([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'Pending'
        ]);

        return response()->json(['message' => 'Leave request submitted successfully', 'data' => $leave], 201);
    }
    public function pendingLeaves(Request $request)
    {
        $user = $request->user();
        $query = Leave::with('employee')
            ->where('status', 'Pending');

        if ($user->role !== 'Super Admin') {
            $query->where('company_id', $user->company_id);
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $leaves]);
    }

    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'comment' => 'nullable|string'
        ]);

        $leave->update([
            'status' => $request->status,
            'reason' => $request->comment // Or use a dedicated column if available
        ]);

        return response()->json(['message' => 'Leave status updated successfully', 'data' => $leave]);
    }
}
