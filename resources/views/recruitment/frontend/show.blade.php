@extends('layouts.public')

@section('title', $job->title)

@section('content')
<div class="hero-section text-start pb-5">
    <div class="container py-lg-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('public.jobs.index') }}" class="text-decoration-none text-muted">All Opportunities</a></li>
                <li class="breadcrumb-item active fw-bold text-primary-dark" aria-current="page">{{ $job->title }}</li>
            </ol>
        </nav>
        
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-indigo-subtle text-indigo p-2 rounded-3 shadow-sm" style="width:48px; height:48px; display:flex; align-items:center; justify-content:center;">
                        <i class="fa-solid fa-briefcase fs-4"></i>
                    </div>
                    <div>
                        <span class="badge bg-indigo-subtle text-indigo badge-pill mb-1">{{ $job->category ?: 'General' }}</span>
                        <h1 class="fw-800 m-0" style="font-size: 2.5rem; letter-spacing: -0.04em; color: var(--text-main);">{{ $job->title }}</h1>
                    </div>
                </div>
                
                <div class="d-flex flex-wrap gap-4 text-muted font-semibold small mt-4">
                    <span><i class="fa-solid fa-building me-2 text-primary opacity-75"></i> {{ $job->company->name }}</span>
                    <span><i class="fa-solid fa-location-dot me-2 text-primary opacity-75"></i> {{ $job->location ?: 'Anywhere' }}</span>
                    <span><i class="fa-solid fa-clock me-2 text-primary opacity-75"></i> {{ $job->type }}</span>
                    @if($job->salary_range)
                        <span><i class="fa-solid fa-money-bill-wave me-2 text-primary opacity-75"></i> {{ $job->salary_range }}</span>
                    @endif
                    <span><i class="fa-solid fa-calendar-day me-2 text-primary opacity-75"></i> Posted {{ $job->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="#apply-form" class="btn btn-primary px-5 py-3 shadow-lg rounded-pill">Apply Now <i class="fa-solid fa-chevron-right ms-2 small"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5 mt-n4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if(session('success'))
            <div class="alert bg-white shadow-lg border-0 mb-5 p-5 text-center animate-fade-in" style="border-radius: 30px;">
                <div class="bg-success text-white p-3 rounded-circle mx-auto mb-4" style="width:80px; height:80px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-check fs-1"></i>
                </div>
                <h2 class="fw-bold mb-3">Application Received!</h2>
                <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 500px;">{{ session('success') }}</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('public.jobs.index') }}" class="btn btn-outline-primary px-5 py-3 rounded-pill fw-bold">Return to Job Board</a>
                </div>
            </div>
            @else
            <div class="row g-5">
                <!-- Job Description Content -->
                <div class="col-lg-7">
                    <div class="bg-white p-4 p-lg-5 shadow-sm border rounded-4 mb-4">
                        <h4 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="fa-solid fa-align-left text-primary me-3"></i> Job Overview
                        </h4>
                        <div class="job-description" style="line-height: 2; color: #475569; font-size: 1.05rem;">
                            {!! nl2br(e($job->description)) !!}
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <h5 class="fw-bold mb-3">About the Company</h5>
                            <p class="text-muted">{{ $job->company->name }} uses SmartHR to streamline their hiring process and provide a better experience for candidates.</p>
                        </div>
                    </div>
                    
                    <!-- Share Job Card -->
                    <div class="bg-indigo-subtle border p-4 rounded-4 shadow-sm mb-4">
                        <h6 class="fw-bold mb-3">Know someone who would be a great fit?</h6>
                        <div class="d-flex gap-2">
                            <button onclick="copyUrl()" class="btn btn-white border bg-white rounded-3 flex-fill text-start px-3 py-2 small fw-bold">
                                <i class="fa-solid fa-link me-2 text-primary"></i> Copy Job Link
                            </button>
                            <a href="#" class="btn btn-white border bg-white rounded-3 px-3 py-2"><i class="fa-brands fa-linkedin text-muted"></i></a>
                            <a href="#" class="btn btn-white border bg-white rounded-3 px-3 py-2"><i class="fa-brands fa-x-twitter text-muted"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Application Sidebar Form -->
                <div class="col-lg-5" id="apply-form">
                    <div class="card shadow-lg border-0 p-3 p-lg-4 animate-fade-in" style="border-radius: 30px; position: sticky; top: 100px;">
                        <div class="card-body">
                            <div class="mb-4 text-center">
                                <h4 class="fw-800 m-0">Quick Application</h4>
                                <p class="text-muted small mt-1">Takes about 2 minutes to complete</p>
                            </div>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger bg-danger-subtle border-0 rounded-4 small mb-4 p-3 animate-fade-in">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('public.jobs.apply', $job) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label font-bold text-muted small">First Name</label>
                                        <input type="text" name="first_name" class="form-control rounded-3 py-2 border-slate-200" required placeholder="Ex: Sok">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label font-bold text-muted small">Last Name</label>
                                        <input type="text" name="last_name" class="form-control rounded-3 py-2 border-slate-200" required placeholder="Ex: Vibol">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label font-bold text-muted small">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 border-slate-200 text-muted"><i class="fa-solid fa-envelope small"></i></span>
                                            <input type="email" name="email" class="form-control rounded-end-3 py-2 border-slate-200 border-start-0" required placeholder="name@email.com">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label font-bold text-muted small">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 border-slate-200 text-muted"><i class="fa-solid fa-phone small"></i></span>
                                            <input type="text" name="phone" class="form-control rounded-end-3 py-2 border-slate-200 border-start-0" required placeholder="+855 ...">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label font-bold text-muted small">Resume / CV (PDF preferred)</label>
                                        <div class="border border-dashed rounded-4 p-3 text-center bg-light position-relative">
                                            <i class="fa-solid fa-cloud-arrow-up text-primary fs-3 mb-2 d-block"></i>
                                            <p class="small text-muted mb-0">Click to select or drag and drop file here</p>
                                            <input type="file" name="resume" class="form-control position-absolute top-0 start-0 opacity-0 h-100 cursor-pointer" required>
                                        </div>
                                        <div class="form-text small opacity-50 text-center">PDF, DOC, DOCX up to 5MB</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label font-bold text-muted small">Cover Letter (Optional)</label>
                                        <textarea name="cover_letter" class="form-control rounded-4 p-3 border-slate-200" rows="3" placeholder="Briefly introduce yourself..."></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 shadow-lg text-uppercase letter-spacing-wide">
                                            Submit Application <i class="fa-solid fa-paper-plane ms-2 small"></i>
                                        </button>
                                        <div class="d-flex align-items-center justify-content-center gap-2 mt-4 text-muted small opacity-75">
                                            <i class="fa-solid fa-lock text-success tiny"></i> Secured by SmartHR Trusted Cloud
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function copyUrl() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Job link copied to clipboard!');
        });
    }
</script>

<style>
    .fw-800 { font-weight: 800 !important; }
    .letter-spacing-wide { letter-spacing: 0.1em; }
    .border-slate-200 { border-color: #e2e8f0; }
    .cursor-pointer { cursor: pointer; }
    .tiny { font-size: 0.65rem; }
    
    .border-dashed {
        border: 2px dashed #cbd5e1 !important;
        transition: all 0.2s;
    }
    .border-dashed:hover {
        border-color: var(--primary) !important;
        background-color: #f1f5f9 !important;
    }
    
    .mt-n4 { margin-top: -2rem; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection
