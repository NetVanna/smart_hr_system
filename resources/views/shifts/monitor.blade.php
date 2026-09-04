@extends('layouts.app')

@section('title', 'Live Attendance Monitor')

@section('content')
<div class="container-fluid p-0">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <h3 class="fw-bold mb-0 text-dark">Live Attendance Monitor</h3>
                <span class="badge bg-danger d-flex align-items-center gap-1 px-3" style="border-radius: 20px; font-size: 0.8rem; animation: pulse-badge 2s infinite;">
                    <span class="rounded-circle bg-white" style="width: 7px; height: 7px; display: inline-block;"></span> LIVE
                </span>
            </div>
            <p class="text-muted small mb-0">
                <i class="fa-regular fa-clock me-1"></i> {{ now()->format('l, d F Y — H:i') }} &bull; Today's workforce check-in status
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendances.index') }}" class="btn btn-outline-primary btn-pill">
                <i class="fa-solid fa-table-list"></i> Full Log
            </a>
            <a href="{{ route('shifts.index') }}" class="btn btn-primary btn-pill">
                <i class="fa-solid fa-calendar-days"></i> Shift Roster
            </a>
        </div>
    </div>

    {{-- Summary Row --}}
    @php
        $totalCount   = $attendances->count();
        $checkedOut   = $attendances->filter(fn($a) => !is_null($a->check_out))->count();
        $stillIn      = $attendances->filter(fn($a) => is_null($a->check_out))->count();
        $lateCount    = $attendances->filter(function($a) {
            $checkIn = \Carbon\Carbon::parse($a->check_in);
            return $checkIn->format('H:i') > '09:15';
        })->count();
        $onTimeCount  = $totalCount - $lateCount;
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card-attendee blue h-100">
                <div class="stat-label">
                    <span>Total Check-ins</span>
                    <i class="fa-solid fa-fingerprint text-primary opacity-50"></i>
                </div>
                <div class="stat-value blue">{{ $totalCount }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Recorded today</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-attendee green h-100">
                <div class="stat-label">
                    <span>On Time</span>
                    <i class="fa-solid fa-circle-check text-success opacity-50"></i>
                </div>
                <div class="stat-value green">{{ $onTimeCount }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Before 09:15</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-attendee amber h-100">
                <div class="stat-label">
                    <span>Late Arrivals</span>
                    <i class="fa-solid fa-triangle-exclamation text-warning opacity-50"></i>
                </div>
                <div class="stat-value amber">{{ $lateCount }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">After 09:15</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-attendee red h-100">
                <div class="stat-label">
                    <span>Still Active</span>
                    <i class="fa-solid fa-user-clock text-danger opacity-50"></i>
                </div>
                <div class="stat-value red">{{ $stillIn }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Not checked out</small>
            </div>
        </div>
    </div>

    {{-- Attendance Cards Grid --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-users-line text-primary"></i> Today's Workforce Status
            </h6>
        </div>
        <div class="card-body">
            @if($attendances->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-user-clock fs-1 mb-3 text-light"></i>
                    <h5 class="text-muted">No attendance records for today yet.</h5>
                    <p class="small">Employees haven't checked in yet, or the shift hasn't started.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($attendances as $att)
                    @php
                        $checkInTime  = \Carbon\Carbon::parse($att->check_in);
                        $isLate       = $checkInTime->format('H:i') > '09:15';
                        $isCheckedOut = !is_null($att->check_out);
                        $statusLabel  = $isCheckedOut ? 'Checked Out' : ($isLate ? 'Late' : 'Punctual');
                        $statusClass  = $isCheckedOut ? 'secondary' : ($isLate ? 'warning' : 'success');
                        $statusIcon   = $isCheckedOut ? 'fa-right-from-bracket' : ($isLate ? 'fa-clock' : 'fa-circle-check');
                        $borderColor  = $isCheckedOut ? '#94a3b8' : ($isLate ? '#f59e0b' : '#22c55e');
                        $initials     = strtoupper(substr(optional($att->employee)->first_name ?? 'E', 0, 1));
                    @endphp
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $borderColor }} !important; border-radius: 14px;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    {{-- Avatar --}}
                                    @if(optional($att->employee)->profile_photo)
                                        <img src="{{ asset('storage/' . $att->employee->profile_photo) }}" class="rounded-circle" width="46" height="46" style="object-fit: cover; border: 2px solid {{ $borderColor }};">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width: 46px; height: 46px; flex-shrink: 0; background: linear-gradient(135deg, #2563eb, #7c3aed); font-size: 1.1rem;">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate">
                                            {{ optional($att->employee)->first_name }} {{ optional($att->employee)->last_name }}
                                        </h6>
                                        <small class="text-muted d-block text-truncate">{{ optional($att->employee)->position ?? 'Employee' }}</small>
                                        {{-- Branch Tag --}}
                                        @if(optional($att->employee)->branch)
                                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.68rem;">
                                                <i class="fa-solid fa-location-dot me-1"></i>{{ $att->employee->branch->name ?? 'Main' }}
                                            </span>
                                        @endif
                                    </div>
                                    {{-- Status Badge --}}
                                    <span class="badge bg-{{ $statusClass }} {{ $statusClass === 'warning' ? 'text-dark' : '' }} d-flex align-items-center gap-1" style="font-size: 0.72rem; border-radius: 10px; white-space: nowrap;">
                                        <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                                    </span>
                                </div>

                                {{-- Time Info --}}
                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <div class="p-2 rounded-3" style="background: #f0fdf4;">
                                            <small class="text-muted d-block" style="font-size: 0.68rem;">CHECK IN</small>
                                            <strong class="text-success" style="font-size: 0.95rem;">{{ $checkInTime->format('H:i') }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded-3" style="background: #f8fafc;">
                                            <small class="text-muted d-block" style="font-size: 0.68rem;">CHECK OUT</small>
                                            <strong class="text-{{ $isCheckedOut ? 'secondary' : 'muted' }}" style="font-size: 0.95rem;">
                                                {{ $isCheckedOut ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '--:--' }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>

                                {{-- Footer: Method & Geofence --}}
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-mobile-screen me-1"></i>{{ $att->method ?? 'Mobile App' }}
                                    </small>
                                    @php
                                        $geoStatus  = $att->location_status ?? 'Within Geofence';
                                        $geoOk      = str_contains(strtolower($geoStatus), 'within') || str_contains(strtolower($geoStatus), 'geofenced');
                                    @endphp
                                    <span class="badge {{ $geoOk ? 'bg-success' : 'bg-warning text-dark' }} bg-opacity-10 border {{ $geoOk ? 'border-success' : 'border-warning' }}" style="font-size: 0.67rem;">
                                        <i class="fa-solid fa-location-dot me-1"></i>{{ $geoOk ? 'Geofenced ✓' : $geoStatus }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes pulse-badge {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.55; }
}
</style>
@endsection
