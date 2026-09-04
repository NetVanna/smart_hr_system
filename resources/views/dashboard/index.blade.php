@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="container-fluid p-0">
    <!-- Welcome Banner / Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 52px; height: 52px; background: linear-gradient(135deg, #2563EB, #1D4ED8); font-size: 1.25rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="mb-1 fw-bold text-dark">{{ auth()->user()->name }} <span class="fs-5">👋</span></h3>
                    <p class="text-muted mb-0 small">
                        <span class="badge bg-light text-primary border me-1">{{ auth()->user()->role }}</span>
                        {{ auth()->user()->company->name ?? 'Attendee Workforce' }} &bull; {{ now()->format('l, F d, Y') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
            @if(auth()->user()->role === 'Company Admin')
                <a href="{{ route('company.users.create') }}" class="btn btn-outline-primary btn-pill">
                    <i class="fa-solid fa-user-plus"></i> {{ __('messages.invite_member') ?? 'Invite Member' }}
                </a>
                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-pill">
                    <i class="fa-solid fa-plus"></i> {{ __('messages.add_employee') ?? 'Add Employee' }}
                </a>
            @elseif(auth()->user()->role === 'HR Manager')
                <a href="{{ route('shifts.monitor') }}" class="btn btn-outline-danger btn-pill">
                    <i class="fa-solid fa-eye"></i> Live Monitor
                </a>
                <a href="{{ route('payrolls.create') }}" class="btn btn-primary btn-pill">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Generate Payslip
                </a>
            @else
                <a href="{{ route('employees.index') }}" class="btn btn-outline-primary btn-pill">
                    <i class="fa-solid fa-users"></i> {{ __('messages.employees') }}
                </a>
                <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-pill">
                    <i class="fa-solid fa-plus"></i> {{ __('messages.apply_leave') }}
                </a>
            @endif
        </div>
    </div>

    {{-- ================= Company Admin Executive Bar ================= --}}
    @if(auth()->user()->role === 'Company Admin')
    <div class="card card-attendee border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-left: 5px solid #2563EB !important;">
        <div class="row g-4 align-items-center">
            <!-- Financial Estimated Monthly Payroll -->
            <div class="col-12 col-md-4 border-md-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-primary" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="fa-solid fa-file-invoice-dollar fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Monthly Estimated Payroll</div>
                        <div class="fs-4 fw-bold text-dark mb-0">${{ number_format($adminMetrics['monthlyPayrollUSD'] ?? 0, 2) }}</div>
                        <small class="text-muted" style="font-size: 0.74rem;">៛ {{ number_format($adminMetrics['monthlyPayrollKHR'] ?? 0, 0) }} KHR (Rate: {{ number_format($adminMetrics['exchangeRate'] ?? 4100, 0) }})</small>
                    </div>
                </div>
            </div>

            <!-- Subscription & Plan Quota -->
            <div class="col-12 col-md-4 border-md-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-success" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="fa-solid fa-shield-halved fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">SaaS Plan: <strong>{{ $adminMetrics['planName'] ?? 'Basic' }}</strong></span>
                            @if(($adminMetrics['planStatus'] ?? '') === 'Active')
                                <span class="badge badge-attendee-active px-2 py-0" style="font-size: 0.68rem;">Active</span>
                            @else
                                <span class="badge badge-attendee-pending px-2 py-0" style="font-size: 0.68rem;">{{ $adminMetrics['planStatus'] ?? 'Trial' }}</span>
                            @endif
                        </div>
                        <div class="progress mb-1" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $adminMetrics['quotaUsagePercent'] ?? 0 }}%;"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                            <span>{{ $stats['totalEmployees'] }} of {{ $adminMetrics['maxEmployees'] }} staff used</span>
                            <a href="{{ route('company.billing.index') }}" class="text-primary text-decoration-none fw-semibold">Billing &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Organization Scale -->
            <div class="col-12 col-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-info" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div>
                            <div class="small fw-bold text-dark">{{ $stats['totalBranches'] ?? 1 }} Branches</div>
                            <small class="text-muted">Cambodia Locations</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-warning" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="small fw-bold text-dark">{{ $stats['totalUsers'] ?? 1 }} Users</div>
                            <small class="text-muted">Admins & Managers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ================= HR Manager Operations Toolbar ================= --}}
    @if(auth()->user()->role === 'HR Manager')
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 5px solid #16a34a !important;">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge bg-success px-3 py-2" style="font-size: 0.8rem;"><i class="fa-solid fa-user-tie me-1"></i> HR Manager Operations</span>
            <small class="text-muted">{{ now()->format('l, F d, Y') }}</small>
        </div>
        <div class="row g-3">
            {{-- Attendance Health --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('attendances.index') }}" class="text-decoration-none">
                    <div class="bg-white rounded-4 p-3 text-center shadow-xs border" style="border-color: #bbf7d0 !important;">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-fingerprint text-success"></i>
                        </div>
                        <div class="fw-bold fs-5 text-dark">{{ $attendanceStats['checkedIn'] ?? 0 }}</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Checked-In Today</small>
                    </div>
                </a>
            </div>
            {{-- Pending Leaves --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('leaves.index') }}?status=Pending" class="text-decoration-none">
                    <div class="bg-white rounded-4 p-3 text-center shadow-xs border" style="border-color: #fef9c3 !important;">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-hourglass-half text-warning"></i>
                        </div>
                        <div class="fw-bold fs-5 text-dark">{{ $stats['pendingLeaves'] ?? 0 }}</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Pending Leaves</small>
                    </div>
                </a>
            </div>
            {{-- Active Shifts --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('shifts.index') }}" class="text-decoration-none">
                    <div class="bg-white rounded-4 p-3 text-center shadow-xs border" style="border-color: #dbeafe !important;">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-business-time text-primary"></i>
                        </div>
                        <div class="fw-bold fs-5 text-dark">{{ $activeShiftsCount ?? 0 }}</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Work Shifts</small>
                    </div>
                </a>
            </div>
            {{-- Monthly Payroll Run --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('payrolls.index') }}" class="text-decoration-none">
                    <div class="bg-white rounded-4 p-3 text-center shadow-xs border" style="border-color: #fce7f3 !important;">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-file-invoice-dollar text-danger"></i>
                        </div>
                        <div class="fw-bold fs-5 text-dark">{{ $monthPayrollCount ?? 0 }}</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Payslips This Month</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Mobile-Inspired 4-Quadrant Metric Cards (Screenshot 3 & 4) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-attendee blue h-100">
                <div class="stat-label">
                    <span>{{ __('messages.total_employees') }}</span>
                    <i class="fa-solid fa-user-group text-primary opacity-75"></i>
                </div>
                <div class="stat-value blue">{{ $stats['totalEmployees'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.76rem;">Registered workforce</small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-attendee green h-100">
                <div class="stat-label">
                    <span>{{ __('messages.present_today') }}</span>
                    <i class="fa-solid fa-circle-check text-success opacity-75"></i>
                </div>
                <div class="stat-value green">{{ $stats['presentToday'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.76rem;">Checked-in on schedule</small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-attendee amber h-100">
                <div class="stat-label">
                    <span>{{ __('messages.on_leave') }}</span>
                    <i class="fa-solid fa-calendar-minus text-warning opacity-75"></i>
                </div>
                <div class="stat-value amber">{{ $stats['onLeave'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.76rem;">Approved leaves today</small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-attendee red h-100">
                <div class="stat-label">
                    <span>{{ __('messages.pending') }} {{ __('messages.leaves') }}</span>
                    <i class="fa-solid fa-clock-rotate-left text-danger opacity-75"></i>
                </div>
                <div class="stat-value red">{{ $stats['pendingLeaves'] ?? 0 }}</div>
                <small class="text-muted" style="font-size: 0.76rem;">Awaiting manager review</small>
            </div>
        </div>
    </div>

    <!-- Attendance Today Overview & Recent Activities -->
    <div class="row g-4">
        <!-- Today Attendance Widget (Inspired by Mobile Home Page Screens 12 & 13) -->
        <div class="col-12 col-lg-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-regular fa-clock text-primary"></i> {{ __('messages.attendance_overview') }}
                    </h6>
                    <a href="{{ route('attendances.index') }}" class="small text-primary text-decoration-none fw-semibold">
                        View All <i class="fa-solid fa-arrow-right fs-xs"></i>
                    </a>
                </div>
                <div class="card-body">
                    <!-- Quick Attendance Summary Boxes (Screens 12 & 13) -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-4" style="background: #EFF6FF; border: 1.5px solid #DBEAFE;">
                                <div class="d-flex align-items-center gap-2 mb-1 text-primary small fw-semibold">
                                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Standard Shift In
                                </div>
                                <h4 class="fw-bold mb-0 text-dark">09:00 am</h4>
                                <small class="text-success fw-semibold"><i class="fa-solid fa-circle-check"></i> On Time Grace</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-4" style="background: #F8FAFC; border: 1.5px solid #E2E8F0;">
                                <div class="d-flex align-items-center gap-2 mb-1 text-muted small fw-semibold">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Standard Shift Out
                                </div>
                                <h4 class="fw-bold mb-0 text-dark">06:00 pm</h4>
                                <small class="text-muted">Standard 8h Day</small>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark mb-3 small text-uppercase tracking-wider">Today's Check-ins</h6>
                    @if(isset($recentAttendances) && $recentAttendances->count() > 0)
                        <div class="list-group list-group-flush border-0">
                            @foreach($recentAttendances as $att)
                                <div class="list-group-item px-0 py-2 d-flex align-items-center justify-content-between border-bottom-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 38px; height: 38px; border: 1.5px solid #DBEAFE;">
                                            {{ substr(optional($att->employee)->first_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark">{{ optional($att->employee)->first_name }} {{ optional($att->employee)->last_name }}</span>
                                            <br>
                                            <small class="text-muted">{{ optional($att->employee)->position ?? 'Employee' }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success">{{ \Carbon\Carbon::parse($att->check_in)->format('h:i A') }}</span>
                                        <br>
                                        <small class="text-muted" style="font-size: 0.72rem;">via {{ $att->method }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fa-solid fa-user-clock fs-2 mb-2 text-light"></i>
                            <p class="mb-0 small">No check-ins recorded yet for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Leave Requests & Quick Approvals (Screenshot 4 & 5) -->
        <div class="col-12 col-lg-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-regular fa-calendar-check text-primary"></i> {{ __('messages.leaves') }}
                    </h6>
                    <a href="{{ route('leaves.index') }}" class="small text-primary text-decoration-none fw-semibold">
                        View All <i class="fa-solid fa-arrow-right fs-xs"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentLeaves) && $recentLeaves->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentLeaves as $leave)
                                <div class="p-3 rounded-4" style="background: #F8FAFC; border: 1px solid #EEF2F6;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center text-primary fw-bold shadow-xs" style="width: 32px; height: 32px; font-size: 0.8rem; border: 1px solid #E2E8F0;">
                                                {{ substr(optional($leave->employee)->first_name ?? 'E', 0, 1) }}
                                            </div>
                                            <div>
                                                <strong class="text-dark small d-block">{{ optional($leave->employee)->first_name }} {{ optional($leave->employee)->last_name }}</strong>
                                                <span class="text-muted" style="font-size: 0.74rem;">{{ $leave->leave_type }}</span>
                                            </div>
                                        </div>
                                        @if($leave->status === 'Approved')
                                            <span class="badge bg-success">{{ __('messages.approved') }}</span>
                                        @elseif($leave->status === 'Rejected')
                                            <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ __('messages.pending') }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center text-muted small mt-2 pt-2 border-top">
                                        <span style="font-size: 0.76rem;"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span>
                                        @if(in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager']))
                                            @if($leave->status === 'Pending')
                                                <div class="d-flex gap-1">
                                                    <form action="{{ route('leaves.quickApprove', $leave) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success py-0 px-2" style="font-size: 0.7rem; border-radius: 6px;"><i class="fa-solid fa-check"></i></button>
                                                    </form>
                                                    <form action="{{ route('leaves.quickReject', $leave) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger py-0 px-2" style="font-size: 0.7rem; border-radius: 6px;"><i class="fa-solid fa-xmark"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;"><i class="fa-solid fa-pen-to-square"></i></a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fa-regular fa-calendar-check fs-2 mb-2 text-light"></i>
                            <p class="mb-0 small">No leave requests found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection