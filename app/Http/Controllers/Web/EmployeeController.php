<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = \App\Models\Employee::with('department')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:employees',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'profile_photo' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        \App\Models\Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(\App\Models\Employee $employee)
    {
        $departments = \App\Models\Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, \App\Models\Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'status' => 'required|in:Active,Inactive,On Leave,Terminated',
            'profile_photo' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($employee->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($employee->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(\App\Models\Employee $employee)
    {
        if ($employee->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($employee->profile_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->profile_photo);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function export()
    {
        $user = request()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $employees = \App\Models\Employee::where('company_id', $user->company_id)->with('department')->get();
        $fileName = 'employees_export_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Employee ID', 'First Name', 'Last Name', 'Email', 'Department', 'Position', 'Joining Date', 'Salary', 'Status'];

        $callback = function() use($employees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_id,
                    $employee->first_name,
                    $employee->last_name,
                    $employee->email,
                    $employee->department->name ?? '',
                    $employee->position,
                    $employee->joining_date,
                    $employee->salary,
                    $employee->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // Skip header row

        $user = request()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $company_id = $user->company_id;
        $count = 0;

        while (($row = fgetcsv($handle)) !== FALSE) {
            if (count($row) < 3) continue; // Basic validation

            \App\Models\Employee::updateOrCreate(
                ['employee_id' => $row[0]], // Key to match
                [
                    'company_id' => $company_id,
                    'first_name' => $row[1],
                    'last_name' => $row[2],
                    'email' => $row[3] ?? null,
                    'position' => $row[5] ?? null,
                    'joining_date' => $row[6] ?? null,
                    'salary' => is_numeric($row[7] ?? '') ? $row[7] : 0,
                    'status' => $row[8] ?? 'Active'
                ]
            );
            $count++;
        }

        fclose($handle);

        return redirect()->route('employees.index')->with('success', "Imported $count employees successfully.");
    }

    public function showIdCard(\App\Models\Employee $employee)
    {
        $employee->load('department');
        $company = $employee->company;
        return view('employees.id_card', compact('employee', 'company'));
    }
}
