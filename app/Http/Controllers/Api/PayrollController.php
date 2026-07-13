<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;

class PayrollController extends Controller
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

        $payrolls = Payroll::where('employee_id', $employee->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json(['data' => $payrolls]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->employee_id) {
            return response()->json(['message' => 'User not linked to an employee profile'], 400);
        }

        $employee = Employee::where('employee_id', $user->employee_id)->first();
        if (!$employee) {
            return response()->json(['message' => 'Employee profile not found'], 404);
        }

        $payroll = Payroll::where('employee_id', $employee->id)->findOrFail($id);

        // Add dual currency logic (Placeholder for Cambodian exchange rate logic)
        $exchangeRate = 4100; // Mock rate 1 USD = 4100 KHR
        $payroll->net_salary_khr = $payroll->net_salary * $exchangeRate;

        return response()->json(['data' => $payroll]);
    }
}
