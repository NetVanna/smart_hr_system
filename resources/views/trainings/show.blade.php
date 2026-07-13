@extends('layouts.app')

@section('title', 'Manage Training')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Course Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <span class="badge bg-light text-primary mb-2">{{ $training->category ?: 'General' }}</span>
                    <h3 class="fw-bold mb-2">{{ $training->name }}</h3>
                    <p class="text-muted">{{ $training->description }}</p>
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Status</span>
                        @if($training->is_mandatory)
                            <span class="badge bg-danger">Mandatory</span>
                        @else
                            <span class="badge bg-light text-dark border">Optional</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Start Date</span>
                        <strong>{{ $training->start_date ?: 'TBA' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">End Date</span>
                        <strong>{{ $training->end_date ?: 'TBA' }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <!-- Participants List -->
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Participants ({{ $training->participants->count() }})</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
                    <i class="fa-solid fa-user-plus me-1"></i> Enroll Employees
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Employee</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($training->participants as $participant)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        @if($participant->employee->profile_photo)
                                            <img src="{{ asset('storage/' . $participant->employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="28" height="28" style="object-fit:cover;">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                                <span class="text-secondary small fw-bold" style="font-size: 10px;">{{ substr($participant->employee->first_name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <strong class="d-block">{{ $participant->employee->first_name }} {{ $participant->employee->last_name }}</strong>
                                            <small class="text-muted">{{ $participant->employee->position }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('trainings.update_status', $participant) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm border-0 bg-light py-0" onchange="this.form.submit()" style="width: auto;">
                                            <option {{ $participant->status == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option {{ $participant->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option {{ $participant->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option {{ $participant->status == 'Failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('trainings.update_status', $participant) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <input type="number" name="score" value="{{ $participant->score }}" class="form-control form-control-sm border-0 bg-light text-center py-0" onchange="this.form.submit()" style="width: 60px;">
                                        <input type="hidden" name="status" value="{{ $participant->status }}">
                                    </form>
                                </td>
                                <td class="pe-3 text-end">
                                    @if($participant->status == 'Completed')
                                        <span class="text-success"><i class="fa-solid fa-circle-check"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($training->participants->isEmpty())
                                <tr><td colspan="4" class="text-center py-4 text-muted">No employees enrolled yet.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('trainings.enroll', $training) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enroll Employees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Select employees to enroll in "{{ $training->name }}"</p>
                    <div class="list-group scroll-y" style="max-height: 300px;">
                        @foreach($employees as $employee)
                        <label class="list-group-item d-flex justify-content-between align-items-center cursor-pointer">
                            <div class="d-flex align-items-center">
                                @if($employee->profile_photo)
                                    <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <span class="text-secondary small fw-bold">{{ substr($employee->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <strong class="d-block">{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                                    <small class="text-muted">{{ $employee->position }}</small>
                                </div>
                            </div>
                            <input class="form-check-input" type="checkbox" name="employee_ids[]" value="{{ $employee->id }}">
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll Selected</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .scroll-y { overflow-y: auto; }
</style>
@endsection
