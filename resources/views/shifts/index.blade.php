@extends('layouts.app')

@section('title', 'Shift Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h3><i class="fa-solid fa-clock-rotate-left me-2"></i> Work Shifts</h3>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShiftModal">
            <i class="fa-solid fa-plus me-1"></i> New Shift
        </button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignShiftModal">
            <i class="fa-solid fa-calendar-check me-1"></i> Assign Shifts
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Existing Shifts</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($shifts as $shift)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $shift->name }}</strong><br>
                            <small class="text-muted">{{ $shift->start_time }} - {{ $shift->end_time }}</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">Active</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-3 text-muted">No shifts created.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Upcoming Assignments</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Employee</th>
                                <th>Shift</th>
                                <th class="text-end pe-4">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr>
                                <td class="ps-4">{{ \Carbon\Carbon::parse($assignment->date)->format('D, d M') }}</td>
                                <td>{{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}</td>
                                <td><span class="badge bg-info text-dark">{{ $assignment->shift->name }}</span></td>
                                <td class="text-end pe-4">{{ $assignment->shift->start_time }} - {{ $assignment->shift->end_time }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No assignments yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Shift Modal -->
<div class="modal fade" id="addShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('shifts.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create New Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Shift Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Morning Shift" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Shift</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Shift Modal -->
<div class="modal fade" id="assignShiftModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('shifts.assign') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Assign Shifts (Rotation)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Select Employees</label>
                        <select name="employee_ids[]" class="form-select" multiple size="5" required>
                            @foreach(\App\Models\Employee::where('company_id', auth()->user()->company_id)->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Select Shift</label>
                        <select name="shift_id" class="form-select" required>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Assign Shift</button>
            </div>
        </form>
    </div>
</div>
@endsection
