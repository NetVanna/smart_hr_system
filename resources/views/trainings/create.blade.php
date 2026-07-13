@extends('layouts.app')

@section('title', 'Create Training Course')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-graduation-cap me-2 text-primary"></i> Register New Training Course</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('trainings.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Health & Safety, Advanced Excel">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" placeholder="e.g. Compliance, Skill Up">
                        </div>
                        <div class="col-md-6 d-flex align-items-end pb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_mandatory" id="isMandatory" value="1">
                                <label class="form-check-label" for="isMandatory">Mandatory Course</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Course Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="What will participants learn?"></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('trainings.index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary px-5">Create Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
