@extends('layouts.app')

@section('title', __('messages.branches'))

@section('content')
<div class="container-fluid p-0">
    <!-- Header with Action -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ __('messages.branches') }}</h3>
            <p class="text-muted small mb-0">{{ __('messages.branch_management') }} • Cambodia Multi-Location</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('branches.create') }}" class="btn btn-primary btn-pill shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> {{ __('messages.create_branch') }}
            </a>
        </div>
    </div>

    <!-- 4 Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee blue h-100">
                <div class="stat-label">
                    <span>{{ __('messages.total_branches') }}</span>
                    <i class="fa-solid fa-building-flag text-primary opacity-50"></i>
                </div>
                <div class="stat-value blue">{{ $totalBranches }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Registered locations</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee green h-100">
                <div class="stat-label">
                    <span>{{ __('messages.active_branches') }}</span>
                    <i class="fa-solid fa-circle-check text-success opacity-50"></i>
                </div>
                <div class="stat-value green">{{ $activeBranches }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Operational branches</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee amber h-100">
                <div class="stat-label">
                    <span>{{ __('messages.assigned_employees') }}</span>
                    <i class="fa-solid fa-users text-warning opacity-50"></i>
                </div>
                <div class="stat-value amber">{{ $assignedStaff }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">Staff at branches</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-attendee blue h-100">
                <div class="stat-label">
                    <span>{{ __('messages.gps_protected') }}</span>
                    <i class="fa-solid fa-location-crosshairs text-info opacity-50"></i>
                </div>
                <div class="stat-value blue">{{ $geofencedBranches }}</div>
                <small class="text-muted" style="font-size: 0.74rem;">With geofence radar</small>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 py-2 px-3 mb-3 small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Card & Filter Bar -->
    <div class="card card-attendee shadow-sm">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <!-- Search -->
            <form action="{{ route('branches.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 9999px 0 0 9999px;">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control form-control-sm bg-light border-start-0" style="border-radius: 0 9999px 9999px 0;" placeholder="Search name, code, province..." value="{{ request('search') }}">
                </div>
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            </form>

            <!-- Status Tabs -->
            <div class="d-flex gap-1 bg-light p-1 rounded-pill">
                <a href="{{ route('branches.index', array_merge(request()->except('status'), ['status' => ''])) }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'text-muted' }} rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                    {{ __('messages.all') }}
                </a>
                <a href="{{ route('branches.index', array_merge(request()->except('status'), ['status' => 'active'])) }}" class="btn btn-sm {{ request('status') === 'active' ? 'btn-primary' : 'text-muted' }} rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                    {{ __('messages.active') }}
                </a>
                <a href="{{ route('branches.index', array_merge(request()->except('status'), ['status' => 'inactive'])) }}" class="btn btn-sm {{ request('status') === 'inactive' ? 'btn-primary' : 'text-muted' }} rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                    {{ __('messages.inactive') }}
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-attendee align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">{{ __('messages.branch_name') }}</th>
                            <th>{{ __('messages.branch_code') }}</th>
                            <th>{{ __('messages.branch_address') }}</th>
                            <th>{{ __('messages.branch_phone') }}</th>
                            <th>{{ __('messages.gps_coordinates') }}</th>
                            <th>{{ __('messages.geofence_radius') }}</th>
                            <th>{{ __('messages.assigned_staff') }}</th>
                            <th>{{ __('messages.branch_status') }}</th>
                            <th class="pe-4 text-end">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-icon-attendee" style="width: 36px; height: 36px; border-radius: 10px; background: #EFF6FF; color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $branch->name }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">ID #BR-{{ str_pad($branch->id, 3, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($branch->code)
                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-family: monospace; font-size: 0.78rem;">{{ $branch->code }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark small text-truncate" style="max-width: 220px;" title="{{ $branch->address }}">
                                    {{ $branch->address ?: '—' }}
                                </div>
                            </td>
                            <td>
                                <small class="text-dark">{{ $branch->phone ?: '—' }}</small>
                            </td>
                            <td>
                                @if($branch->latitude && $branch->longitude)
                                    <a href="https://maps.google.com/?q={{ $branch->latitude }},{{ $branch->longitude }}" target="_blank" class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 text-decoration-none" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-map-pin me-1"></i> {{ round($branch->latitude, 4) }}, {{ round($branch->longitude, 4) }}
                                    </a>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-muted" style="font-size: 0.72rem;">No GPS set</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-2 py-1" style="font-size: 0.74rem;">
                                    <i class="fa-solid fa-bullseye me-1"></i> {{ $branch->geofence_radius ?? 100 }} m
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border px-2 py-1" style="font-size: 0.76rem;">
                                    <i class="fa-solid fa-user-group me-1 text-primary"></i> {{ $branch->employees_count }} staff
                                </span>
                            </td>
                            <td>
                                @if($branch->is_active)
                                    <span class="badge-status-attendee on-time">
                                        <span class="dot"></span> {{ __('messages.active') }}
                                    </span>
                                @else
                                    <span class="badge-status-attendee late">
                                        <span class="dot"></span> {{ __('messages.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown d-inline-block">
                                    <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-light border rounded-3 p-1 px-2" title="{{ __('messages.edit_branch') }}">
                                        <i class="fa-solid fa-pen text-muted"></i>
                                    </a>
                                    <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_branch_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border rounded-3 p-1 px-2 text-danger" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="fa-solid fa-building-circle-exclamation fs-1 text-muted opacity-50"></i>
                                </div>
                                <h6>{{ __('messages.no_branches_found') }}</h6>
                                <a href="{{ route('branches.create') }}" class="btn btn-primary btn-sm btn-pill mt-2">
                                    <i class="fa-solid fa-plus me-1"></i> {{ __('messages.create_branch') }}
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($branches->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $branches->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
