@extends('layouts.app')

@section('title', __('messages.leave_management'))

@section('content')
<div class="container-fluid p-0">
    <!-- Header with Action -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1 text-dark">All Leaves</h3>
            <p class="text-muted small mb-0">Track and manage employee time-off and leave balances</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-pill shadow-sm">
                <i class="fa-solid fa-plus"></i> {{ __('messages.apply_leave') }}
            </a>
        </div>
    </div>

    <!-- Mobile-Inspired 4-Stat Metric Cards (Screenshots 3 & 4) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee blue h-100">
                <div class="stat-label">
                    <span>Leave Balance</span>
                    <i class="fa-solid fa-wallet text-primary opacity-50"></i>
                </div>
                <div class="stat-value blue">{{ $leaveStats['balance'] ?? 20 }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Days available / yr</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee green h-100">
                <div class="stat-label">
                    <span>Leave Approved</span>
                    <i class="fa-solid fa-circle-check text-success opacity-50"></i>
                </div>
                <div class="stat-value green">{{ $leaveStats['approved'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Granted requests</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee amber h-100">
                <div class="stat-label">
                    <span>Leave Pending</span>
                    <i class="fa-solid fa-hourglass-half text-warning opacity-50"></i>
                </div>
                <div class="stat-value amber">{{ $leaveStats['pending'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Needs review</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee red h-100">
                <div class="stat-label">
                    <span>Leave Cancelled</span>
                    <i class="fa-solid fa-ban text-danger opacity-50"></i>
                </div>
                <div class="stat-value red">{{ $leaveStats['rejected'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Rejected / Cancelled</small>
            </div>
        </div>
    </div>

    <!-- Main Card with Segmented Tabs and Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3 py-3">
            <!-- Mobile-Inspired Segmented Pill Tabs (Screenshots 3 & 4) -->
            <div class="segmented-pills">
                <button class="nav-link active" onclick="filterLeaveStatus('all', this)">All</button>
                <button class="nav-link" onclick="filterLeaveStatus('Approved', this)">Approved</button>
                <button class="nav-link" onclick="filterLeaveStatus('Pending', this)">Pending</button>
                <button class="nav-link" onclick="filterLeaveStatus('Rejected', this)">Rejected</button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="leaveSearch" class="form-control form-control-sm border-start-0" placeholder="Search employee..." style="border-radius: 0 10px 10px 0;" onkeyup="searchLeaves()">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="leavesTable">
                    <thead>
                        <tr>
                            <th class="ps-4">{{ __('messages.employees') }}</th>
                            <th>{{ __('messages.leave_type') }}</th>
                            <th>{{ __('messages.duration') }}</th>
                            <th>Reason</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="pe-4 text-end">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr class="leave-row" data-status="{{ $leave->status }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-primary" style="width: 38px; height: 38px; background: #EFF6FF; border: 1.5px solid #DBEAFE; font-size: 0.85rem;">
                                        {{ substr(optional($leave->employee)->first_name ?? 'E', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block employee-name">{{ optional($leave->employee)->first_name }} {{ optional($leave->employee)->last_name }}</span>
                                        <small class="text-muted">{{ optional($leave->employee)->position ?? optional($leave->employee)->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-secondary">{{ $leave->leave_type }}</span>
                            </td>
                            <td>
                                <div class="text-dark small fw-medium">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                </div>
                                <small class="text-muted" style="font-size: 0.73rem;">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Days
                                </small>
                            </td>
                            <td>
                                <span class="text-muted small text-truncate d-inline-block" style="max-width: 220px;" title="{{ $leave->reason }}">
                                    {{ $leave->reason ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($leave->status === 'Approved')
                                    <span class="badge bg-success">{{ __('messages.approved') }}</span>
                                @elseif($leave->status === 'Rejected')
                                    <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    @if(in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager']))
                                        @if($leave->status === 'Pending')
                                            {{-- 1-Click Approve --}}
                                            <form action="{{ route('leaves.quickApprove', $leave) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success py-1 px-2" style="font-size: 0.78rem; border-radius: 8px;" title="Approve Leave">
                                                    <i class="fa-solid fa-check"></i> Approve
                                                </button>
                                            </form>
                                            {{-- 1-Click Reject --}}
                                            <form action="{{ route('leaves.quickReject', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this leave request?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 0.78rem; border-radius: 8px;" title="Reject Leave">
                                                    <i class="fa-solid fa-xmark"></i> Reject
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;" title="Edit Status">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endif
                                    @endif
                                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_leave_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 border-0" style="font-size: 0.78rem;" title="Delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open fs-2 mb-2 text-light"></i>
                                <p class="mb-0">No leave requests found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function filterLeaveStatus(status, btn) {
        document.querySelectorAll('.segmented-pills .nav-link').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');

        const rows = document.querySelectorAll('.leave-row');
        rows.forEach(row => {
            if (status === 'all' || row.getAttribute('data-status') === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function searchLeaves() {
        const query = document.getElementById('leaveSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.leave-row');
        rows.forEach(row => {
            const name = row.querySelector('.employee-name').innerText.toLowerCase();
            if (name.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
