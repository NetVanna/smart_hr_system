<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\User;

class RecruitmentController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::withCount('applicants')->orderBy('created_at', 'desc')->get();
        $totalApplicants = Applicant::whereHas('jobPosting', function($q) {
            $q->where('company_id', auth()->user()->company_id);
        })->count();
        $totalInterviews = Interview::whereHas('applicant.jobPosting', function($q) {
            $q->where('company_id', auth()->user()->company_id);
        })->whereDate('scheduled_at', '>=', now()->toDateString())->count();

        return view('recruitment.index', compact('jobs', 'totalApplicants', 'totalInterviews'));
    }

    public function createJob()
    {
        return view('recruitment.jobs.create');
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'category' => 'nullable|string',
            'location' => 'nullable|string',
            'salary_range' => 'nullable|string',
            'closing_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['company_id'] = auth()->user()->company_id;

        JobPosting::create($data);

        return redirect()->route('recruitment.index')->with('success', 'Job posting created successfully.');
    }

    public function showJob(JobPosting $job)
    {
        $job->load('applicants');
        return view('recruitment.jobs.show', compact('job'));
    }

    public function applicants()
    {
        $applicants = Applicant::with('jobPosting')->orderBy('created_at', 'desc')->get();
        return view('recruitment.applicants.index', compact('applicants'));
    }

    public function showApplicant(Applicant $applicant)
    {
        $applicant->load(['jobPosting', 'interviews.interviewer']);
        $users = User::all();
        $departments = \App\Models\Department::where('company_id', auth()->user()->company_id)->get();
        return view('recruitment.applicants.show', compact('applicant', 'users', 'departments'));
    }

    public function hireApplicant(Request $request, Applicant $applicant)
    {
        $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric',
        ]);

        $employee = \App\Models\Employee::create([
            'company_id' => auth()->user()->company_id,
            'employee_id' => $request->employee_id,
            'first_name' => $applicant->first_name,
            'last_name' => $applicant->last_name,
            'email' => $applicant->email,
            'phone' => $applicant->phone,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'status' => 'Active',
        ]);

        $applicant->update(['status' => 'Hired']);

        return redirect()->route('employees.index')->with('success', 'Applicant hired successfully! Employee record created.');
    }

    public function scheduleInterview(Request $request, Applicant $applicant)
    {
        $request->validate([
            'interviewer_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'type' => 'required|string',
        ]);

        Interview::create([
            'applicant_id' => $applicant->id,
            'interviewer_id' => $request->interviewer_id,
            'scheduled_at' => $request->scheduled_at,
            'type' => $request->type,
        ]);

        $applicant->update(['status' => 'Interviewing']);

        return back()->with('success', 'Interview scheduled successfully.');
    }
}
