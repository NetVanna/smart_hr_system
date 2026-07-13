<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Employee;
use App\Models\EmployeeSkill;

class SkillMatrixController extends Controller
{
    public function index()
    {
        $employees = Employee::with('skills')->get();
        $skills = Skill::all();
        $categories = $skills->pluck('category')->unique()->filter();

        return view('skills.matrix', compact('employees', 'skills', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        Skill::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return back()->with('success', 'Skill added to matrix.');
    }

    public function updateProficiency(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'skill_id' => 'required|exists:skills,id',
            'proficiency_level' => 'required|integer|min:0|max:5',
        ]);

        EmployeeSkill::updateOrCreate(
            ['employee_id' => $request->employee_id, 'skill_id' => $request->skill_id],
            ['proficiency_level' => $request->proficiency_level]
        );

        return response()->json(['success' => true]);
    }
}
