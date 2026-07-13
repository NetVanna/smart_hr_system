@extends('layouts.app')

@section('title', 'Global Applicant Tracking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-users-viewfinder me-2"></i> All Applicants</h3>
    <a href="{{ route('recruitment.index') }}" class="btn btn-light">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Jobs
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Applicant</th>
                        <th>Applied For</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applicants as $applicant)
                    <tr>
                        <td class="ps-4">
                            <strong>{{ $applicant->first_name }} {{ $applicant->last_name }}</strong>
                        </td>
                        <td>{{ $applicant->jobPosting->title }}</td>
                        <td>{{ $applicant->email }}</td>
                        <td>
                            <span class="badge {{ $applicant->status === 'Hired' ? 'bg-success' : ($applicant->status === 'Rejected' ? 'bg-danger' : 'bg-info') }}">
                                {{ $applicant->status }}
                            </span>
                        </td>
                        <td><small class="text-muted">{{ $applicant->created_at->format('d M Y') }}</small></td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('recruitment.applicants.show', $applicant) }}" class="btn btn-sm btn-outline-primary">View Profile</a>
                        </td>
                    </tr>
                    @endforeach
                    @if($applicants->isEmpty())
                        <tr><td colspan="6" class="text-center py-4 text-muted">No applications found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
