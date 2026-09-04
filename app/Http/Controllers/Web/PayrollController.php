<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $payrolls = Payroll::with('employee')
            ->where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $employees = Employee::where('company_id', $companyId)->where('status', 'Active')->get();
        $exchangeRate = (float)(auth()->user()->company->exchange_rate ?? 4100);
        return view('payrolls.create', compact('employees', 'exchangeRate'));
    }

    /**
     * AJAX: Return employee's salary for auto-fill in payroll form
     */
    public function getEmployeeSalary(Employee $employee)
    {
        // Security: ensure employee belongs to the logged-in company
        if ($employee->company_id !== auth()->user()->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'salary'        => (float)$employee->salary,
            'employee_name' => "{$employee->first_name} {$employee->last_name}",
            'position'      => $employee->position,
        ]);
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $exchangeRate = (float)(auth()->user()->company->exchange_rate ?? 4100);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer'
        ]);

        $net_salary = $request->basic_salary + $request->allowance - $request->deductions;

        Payroll::create([
            'company_id'  => $companyId,
            'employee_id' => $request->employee_id,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance,
            'deductions' => $request->deductions,
            'net_salary' => $net_salary,
            'month' => $request->month,
            'year' => $request->year,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll generated successfully.');
    }

    public function show(Payroll $payroll)
    {
        // Security: ensure payroll belongs to the logged-in company
        if ($payroll->company_id !== auth()->user()->company_id) {
            abort(403);
        }
        // Generate PDF Payslip
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payrolls.payslip', compact('payroll'));
        return $pdf->stream("payslip_{$payroll->employee->employee_id}_{$payroll->month}_{$payroll->year}.pdf");
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->company_id !== auth()->user()->company_id) {
            abort(403);
        }
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll record deleted.');
    }
}
