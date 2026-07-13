@extends('layouts.app')

@section('title', __('messages.attendance_logs'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('messages.attendance_logs') }}</h2>
    <form action="{{ route('attendances.index') }}" method="GET" class="d-flex w-25">
        <input type="date" name="date" class="form-control me-2" value="{{ request('date', today()->toDateString()) }}">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i></button>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 ps-4">{{ __('messages.employees') }}</th>
                    <th class="border-0">{{ __('messages.date') }}</th>
                    <th class="border-0">{{ __('messages.check_in') }}</th>
                    <th class="border-0">{{ __('messages.check_out') }}</th>
                    <th class="border-0">{{ __('messages.method') }}</th>
                    <th class="border-0">{{ __('messages.geofence') }}</th>
                    <th class="border-0 pe-4">{{ __('messages.gps_location') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $log)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            @if($log->employee->profile_photo)
                                <img src="{{ asset('storage/' . $log->employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <span class="text-secondary small fw-bold">{{ substr($log->employee->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <strong>{{ $log->employee->first_name }} {{ $log->employee->last_name }}</strong><br>
                                <small class="text-muted">{{ $log->employee->employee_id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                    <td>
                        @if($log->check_in)
                            <span class="badge bg-success"><i class="fa-solid fa-arrow-right-to-bracket"></i> {{ $log->check_in }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($log->check_out)
                            <span class="badge bg-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> {{ $log->check_out }}</span>
                        @else
                            <span class="text-muted align-middle">{{ __('messages.pending') }}</span>
                        @endif
                    </td>
                    <td><span class="badge bg-secondary">{{ $log->method }}</span></td>
                    <td>
                        @if($log->within_geofence)
                            <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-location-dot"></i> {{ __('messages.inside') }}</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger" title="Checked in outside authorized area"><i class="fa-solid fa-triangle-exclamation"></i> {{ __('messages.outside') }}</span>
                        @endif
                    </td>
                    <td class="pe-4">
                        @if($log->latitude)
                            <small class="text-muted d-block">{{ $log->latitude }}, {{ $log->longitude }}</small>
                        @endif
                        <span class="small">{{ $log->location ?? 'N/A' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">{{ __('messages.no_logs_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
