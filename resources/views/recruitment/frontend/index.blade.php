@extends('layouts.public')

@section('title', 'Join our Team')

@section('content')
<div class="hero-section">
    <div class="container py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <span class="badge bg-indigo-subtle text-indigo badge-pill mb-3">Careers at SmartHR</span>
                <h1 class="hero-title">Shape the Future of Your Career with Us.</h1>
                <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 600px;">Explore opportunities at some of the world's most innovative companies empowered by SmartHR SaaS platform.</p>
                
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                    <button class="btn btn-light border px-4 py-2 rounded-pill font-semibold">🔍 Explore Roles</button>
                    <button class="btn btn-light border px-4 py-2 rounded-pill font-semibold">📍 Remote Opportunities</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-12 mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0"><i class="fa-solid fa-sparkles text-warning me-2"></i> Current Openings</h4>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">Viewing <strong>{{ $jobs->count() ?: 0 }}</strong> positions</span>
                    <select class="form-select border-0 bg-white shadow-sm rounded-3 py-2 px-3 small font-semibold" style="width: auto;">
                        <option>Sort by: Newest</option>
                        <option>Sort by: Category</option>
                    </select>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($jobs as $job)
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="job-card w-100">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-indigo-subtle text-indigo p-2 rounded-3 shadow-sm" style="width:42px; height:42px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <span class="badge bg-indigo-subtle text-indigo badge-pill">{{ $job->category ?: 'General' }}</span>
                            </div>
                            <h5 class="fw-bold mb-1 text-primary-dark">{{ $job->title }}</h5>
                            <span class="company-name mb-3 d-block"><i class="fa-solid fa-building me-1 opacity-50"></i> {{ $job->company->name }}</span>
                            
                            <div class="d-flex flex-wrap gap-2 mb-4 opacity-75">
                                <span class="badge bg-light text-dark fw-medium border"><i class="fa-solid fa-location-dot me-1 text-muted"></i> {{ $job->location ?: 'Anywhere' }}</span>
                                <span class="badge bg-light text-dark fw-medium border"><i class="fa-solid fa-clock me-1 text-muted"></i> {{ $job->type }}</span>
                                @if($job->salary_range)
                                    <span class="badge bg-light text-dark fw-medium border"><i class="fa-solid fa-money-bill-wave me-1 text-muted"></i> {{ $job->salary_range }}</span>
                                @endif
                            </div>
                            <p class="line-clamp-2 text-muted mb-4">{{ \Illuminate\Support\Str::limit($job->description, 120) }}</p>
                        </div>
                        <div class="text-start mt-auto">
                            <a href="{{ route('public.jobs.show', $job) }}" class="btn btn-primary w-100">
                                Apply for this job <i class="fa-solid fa-arrow-right ms-2 small"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-5 shadow-sm border animate-fade-in">
                        <i class="fa-solid fa-inbox fs-1 text-muted mb-3 d-block opacity-25"></i>
                        <h4 class="text-muted fw-bold">No open positions found.</h4>
                        <p class="text-muted small font-semibold">We're always growing! Check back soon or set up an alert.</p>
                        <button class="btn btn-outline-primary mt-3 px-4 py-2 rounded-pill">Notify me of new roles</button>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .job-card {
        animation: slideUp 0.6s ease-out forwards;
    }
    
    .job-card:nth-child(2) { animation-delay: 0.1s; }
    .job-card:nth-child(3) { animation-delay: 0.2s; }
</style>
@endsection
