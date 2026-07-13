@extends('layouts.app')

@section('title', 'Training & Certifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-graduation-cap me-2"></i> Training Management</h3>
    <a href="{{ route('trainings.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> New Course
    </a>
</div>

<div class="row">
    @foreach($trainings as $training)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge bg-light text-primary px-2 py-1">{{ $training->category ?: 'General' }}</span>
                    @if($training->is_mandatory)
                        <span class="badge bg-danger">Mandatory</span>
                    @endif
                </div>
                <h5 class="fw-bold mb-2">{{ $training->name }}</h5>
                <p class="text-muted small mb-3 text-truncate-2">{{ $training->description }}</p>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <small class="text-muted d-block">Participants</small>
                        <strong class="fs-5">{{ $training->participants_count }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Duration</small>
                        <strong class="small">
                            {{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('M d') : 'Open' }} - 
                            {{ $training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('M d') : 'Open' }}
                        </strong>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3 text-center">
                <a href="{{ route('trainings.show', $training) }}" class="btn btn-outline-primary btn-sm w-100">
                    Manage Course <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
