@extends('layouts.app')

@section('title', 'Recruitment Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-briefcase me-2"></i> Recruitment & ATS</h3>
    <div>
        <a href="{{ route('public.jobs.index') }}" target="_blank" class="btn btn-outline-info me-2">
            <i class="fa-solid fa-globe me-1"></i> Public Job Board
        </a>
        <a href="{{ route('recruitment.applicants.index') }}" class="btn btn-outline-primary me-2">
            <i class="fa-solid fa-users me-1"></i> All Applicants
        </a>
        <a href="{{ route('recruitment.jobs.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Post New Job
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6 class="text-uppercase small">Open Positions</h6>
                <h2 class="mb-0">{{ $jobs->where('status', 'Open')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <h6 class="text-uppercase small">Total Applicants</h6>
                <h2 class="mb-0">{{ $totalApplicants }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-warning text-white">
            <div class="card-body">
                <h6 class="text-uppercase small">Upcoming Interviews</h6>
                <h2 class="mb-0">{{ $totalInterviews }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach($jobs as $job)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge bg-light text-primary px-2 py-1">{{ $job->type }}</span>
                    <span class="badge {{ $job->status === 'Open' ? 'bg-success' : 'bg-secondary' }}">{{ $job->status }}</span>
                </div>
                <h5 class="fw-bold mb-1">{{ $job->title }}</h5>
                <small class="text-muted d-block mb-3"><i class="fa-solid fa-location-dot me-1"></i> {{ $job->location ?: 'Not specified' }}</small>
                
                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3">
                    <div class="text-center flex-fill border-end">
                        <small class="text-muted d-block">Applicants</small>
                        <strong class="fs-5">{{ $job->applicants_count }}</strong>
                    </div>
                </div>

                <p class="text-muted small text-truncate-3 mb-0">{{ $job->description }}</p>
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('recruitment.jobs.show', $job) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        View Applicants <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                    <button class="btn btn-outline-secondary btn-sm" onclick="copyJobLink('{{ route('public.jobs.show', $job) }}')">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    function copyJobLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Job link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>

<style>
    .text-truncate-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
