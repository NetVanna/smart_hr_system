<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;

class PublicRecruitmentController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::where('status', 'Open')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('recruitment.frontend.index', compact('jobs'));
    }

    public function show(JobPosting $job)
    {
        if ($job->status !== 'Open') {
            return redirect()->route('public.jobs.index')->with('error', 'This job posting is no longer active.');
        }
        return view('recruitment.frontend.show', compact('job'));
    }

    public function apply(Request $request, JobPosting $job)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        Applicant::create([
            'job_posting_id' => $job->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'resume_path' => $resumePath,
            'cover_letter' => $request->cover_letter,
            'status' => 'Applied',
        ]);

        return back()->with('success', 'Your application has been submitted successfully! Our HR team will review it and contact you soon.');
    }
}
