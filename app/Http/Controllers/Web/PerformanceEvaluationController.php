<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerformanceEvaluation;
use App\Models\Employee;

class PerformanceEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = PerformanceEvaluation::with(['employee', 'evaluator'])
            ->orderBy('evaluation_date', 'desc')
            ->get();
        return view('evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('evaluations.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|in:Monthly,Quarterly,Annual',
            'evaluation_date' => 'required|date',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'future_goals' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['company_id'] = auth()->user()->company_id;
        $data['evaluator_id'] = auth()->id();

        PerformanceEvaluation::create($data);

        return redirect()->route('evaluations.index')->with('success', 'Performance evaluation recorded successfully.');
    }

    public function show(PerformanceEvaluation $evaluation)
    {
        $evaluation->load(['employee', 'evaluator']);
        return view('evaluations.show', compact('evaluation'));
    }
}
