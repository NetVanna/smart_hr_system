<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = \App\Models\Payroll::with('employee')->orderBy('created_at', 'desc')->get();
        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = \App\Models\Employee::all();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer'
        ]);

        $net_salary = $request->basic_salary + $request->allowance - $request->deductions;

        \App\Models\Payroll::create([
            'employee_id' => $request->employee_id,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance,
            'deductions' => $request->deductions,
            'net_salary' => $net_salary,
            'month' => $request->month,
            'year' => $request->year
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll generated successfully.');
    }

    public function show(\App\Models\Payroll $payroll)
    {
        // Generate PDF Payslip
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payrolls.payslip', compact('payroll'));
        return $pdf->stream("payslip_{$payroll->employee->employee_id}_{$payroll->month}_{$payroll->year}.pdf");
    }

    public function destroy(\App\Models\Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll record deleted.');
    }
}
