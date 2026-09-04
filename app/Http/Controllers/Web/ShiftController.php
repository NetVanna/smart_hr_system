<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::where('company_id', auth()->user()->company_id)->get();
        $assignments = ShiftAssignment::with(['employee', 'shift'])
            ->whereHas('employee', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get();

        return view('shifts.index', compact('shifts', 'assignments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Shift::create($validated);

        return back()->with('success', 'Shift created successfully.');
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $count = 0;
        while ($startDate <= $endDate) {
            foreach ($validated['employee_ids'] as $employeeId) {
                ShiftAssignment::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date' => $startDate->toDateString()
                    ],
                    ['shift_id' => $validated['shift_id']]
                );
                $count++;
            }
            $startDate->addDay();
        }

        return back()->with('success', "Assigned shifts for $count man-days.");
    }

    public function monitor()
    {
        // Live Attendance Monitor for HR Manager
        $today = Carbon::today()->toDateString();
        $attendances = \App\Models\Attendance::whereDate('date', $today)
            ->with(['employee.branch'])
            ->latest('check_in')
            ->get();
            
        return view('shifts.monitor', compact('attendances'));
    }
}
