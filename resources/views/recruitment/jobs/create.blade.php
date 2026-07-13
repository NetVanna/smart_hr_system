@extends('layouts.app')

@section('title', 'Post New Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-briefcase me-2 text-primary"></i> Create Job Posting</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('recruitment.jobs.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Senior Laravel Developer">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Job Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Remote">Remote</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" placeholder="e.g. Engineering, Marketing">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Phnom Penh, Cambodia">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Salary Range</label>
                            <input type="text" name="salary_range" class="form-control" placeholder="e.g. $1000 - $1500">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Closing Date</label>
                        <input type="date" name="closing_date" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Job Description</label>
                        <textarea name="description" class="form-control" rows="6" placeholder="Describe the role and requirements..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('recruitment.index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary px-5">Post Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
