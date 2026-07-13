@extends('layouts.app')

@section('title', 'Applicant Profile')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Applicant Details</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-user-tie text-primary fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $applicant->first_name }} {{ $applicant->last_name }}</h4>
                    <span class="badge {{ $applicant->status === 'Hired' ? 'bg-success' : ($applicant->status === 'Rejected' ? 'bg-danger' : 'bg-info') }} mb-3">
                        {{ $applicant->status }}
                    </span>
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Job Posting</span>
                        <strong class="text-end">{{ $applicant->jobPosting->title }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Email</span>
                        <strong>{{ $applicant->email }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Phone</span>
                        <strong>{{ $applicant->phone ?: 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Applied On</span>
                        <strong>{{ $applicant->created_at->format('d M Y') }}</strong>
                    </li>
                </ul>

                <hr>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                        Update Status
                    </button>
                    @if($applicant->status !== 'Hired' && ($applicant->status === 'Offered' || $applicant->status === 'Interviewing'))
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#hireModal">
                            <i class="fa-solid fa-user-plus me-1"></i> Hire Now
                        </button>
                    @endif
                    @if($applicant->resume_path)
                        <a href="{{ asset('storage/' . $applicant->resume_path) }}" target="_blank" class="btn btn-primary">
                            <i class="fa-solid fa-file-pdf me-1"></i> View Resume
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <!-- Interviews -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Interviews & Feedback</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                    <i class="fa-solid fa-calendar-plus me-1"></i> Schedule Interview
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Type</th>
                                <th>Interviewer</th>
                                <th>Scheduled At</th>
                                <th class="pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applicant->interviews as $interview)
                            <tr>
                                <td class="ps-3"><strong>{{ $interview->type }}</strong></td>
                                <td>{{ $interview->interviewer->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($interview->scheduled_at)->format('d M Y, h:i A') }}</td>
                                <td class="pe-3">
                                    <span class="badge {{ \Carbon\Carbon::parse($interview->scheduled_at)->isPast() ? 'bg-secondary' : 'bg-warning' }}">
                                        {{ \Carbon\Carbon::parse($interview->scheduled_at)->isPast() ? 'Completed' : 'Upcoming' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            @if($applicant->interviews->isEmpty())
                                <tr><td colspan="4" class="text-center py-4 text-muted">No interviews scheduled yet.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cover Letter -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Cover Letter / Note</h5>
            </div>
            <div class="card-body bg-light rounded m-3 p-3">
                <p class="mb-0 small">{!! nl2br(e($applicant->cover_letter)) ?: '<em>No cover letter provided.</em>' !!}</p>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('recruitment.applicants.update_status', $applicant) }}" method="POST">
            @csrf
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Update Applicant Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="status" class="form-select" required>
                        <option value="Applied" {{ $applicant->status == 'Applied' ? 'selected' : '' }}>Applied</option>
                        <option value="Interviewing" {{ $applicant->status == 'Interviewing' ? 'selected' : '' }}>Interviewing</option>
                        <option value="Offered" {{ $applicant->status == 'Offered' ? 'selected' : '' }}>Offered</option>
                        <option value="Rejected" {{ $applicant->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Hired" {{ $applicant->status == 'Hired' ? 'selected' : '' }}>Hired</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('recruitment.applicants.schedule', $applicant) }}" method="POST">
            @csrf
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Interviewer</label>
                        <select name="interviewer_id" class="form-select" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scheduled Time</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interview Type</label>
                        <select name="type" class="form-select" required>
                            <option value="Screening">Screening</option>
                            <option value="Technical">Technical</option>
                            <option value="Final">Final</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Now</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Hire Modal -->
<div class="modal fade" id="hireModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('recruitment.applicants.hire', $applicant) }}" method="POST">
            @csrf
            <div class="modal-content text-start">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Convert Applicant to Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        <i class="fa-solid fa-circle-info me-1"></i> 
                        Hiring will automatically create a new Employee record.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control" placeholder="e.g. EMP-1001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select" required>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" value="{{ $applicant->jobPosting->title }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Joining Date</label>
                            <input type="date" name="joining_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monthly Salary ($)</label>
                            <input type="number" name="salary" class="form-control" step="0.01" placeholder="800" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4">Confirm Hire</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
