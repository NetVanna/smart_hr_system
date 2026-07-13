<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Employee;
use App\Models\TrainingParticipant;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::withCount('participants')->orderBy('created_at', 'desc')->get();
        return view('trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('trainings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'is_mandatory' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['company_id'] = auth()->user()->company_id;
        $data['is_mandatory'] = $request->has('is_mandatory');

        Training::create($data);

        return redirect()->route('trainings.index')->with('success', 'Training course created successfully.');
    }

    public function show(Training $training)
    {
        $training->load(['participants.employee']);
        $employees = Employee::whereDoesntHave('trainings', function($query) use ($training) {
            $query->where('training_id', $training->id);
        })->get();

        return view('trainings.show', compact('training', 'employees'));
    }

    public function enroll(Request $request, Training $training)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        foreach ($request->employee_ids as $id) {
            TrainingParticipant::create([
                'training_id' => $training->id,
                'employee_id' => $id,
                'status' => 'Assigned'
            ]);
        }

        return back()->with('success', 'Employees enrolled successfully.');
    }

    public function updateStatus(Request $request, TrainingParticipant $participant)
    {
        $request->validate([
            'status' => 'required|in:Assigned,In Progress,Completed,Failed',
            'score' => 'nullable|integer|min:0|max:100'
        ]);

        $participant->update($request->all());

        return back()->with('success', 'Participant status updated.');
    }
}
