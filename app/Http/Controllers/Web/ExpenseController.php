<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['employee', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('expenses.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string',
            'receipt' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('receipt');
        $data['company_id'] = auth()->user()->company_id;

        if ($request->hasFile('receipt')) {
            $data['receipt_photo'] = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense submitted successfully.');
    }

    public function approve(Expense $expense)
    {
        $expense->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Expense approved.');
    }

    public function reject(Request $request, Expense $expense)
    {
        $request->validate(['reason' => 'required|string']);

        $expense->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Expense rejected.');
    }
}
