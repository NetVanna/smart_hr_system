@extends('layouts.app')

@section('title', 'Live Attendance Monitor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-eye text-danger me-2"></i> Live Attendance Monitor</h3>
    <span class="badge bg-danger pulse-animation">LIVE</span>
</div>

<div class="row">
    @forelse($attendances as $att)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-start border-4 {{ $att->check_out ? 'border-secondary' : 'border-success' }}">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        @if($att->employee->profile_photo)
                            <img src="{{ asset('storage/' . $att->employee->profile_photo) }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                {{ substr($att->employee->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ $att->employee->first_name }} {{ $att->employee->last_name }}</h6>
                        <small class="text-muted">{{ $att->employee->position }}</small>
                    </div>
                </div>
                <div class="row text-center bg-light rounded py-2">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">Check In</small>
                        <strong>{{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Check Out</small>
                        <strong>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '--:--' }}</strong>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $att->location_status ?? 'Geofenced' }}</small>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fa-solid fa-clock shadow-sm p-4 rounded-circle bg-white text-muted mb-3 fs-1"></i>
        <h5 class="text-muted">No attendance recorded today yet.</h5>
    </div>
    @endforelse
</div>

<style>
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
</style>
@endsection
