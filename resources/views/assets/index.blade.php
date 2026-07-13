@extends('layouts.app')

@section('title', 'Asset Tracking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-laptop-code me-2 text-primary"></i> Asset Inventory</h3>
    <a href="{{ route('assets.create') }}" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add New Asset
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body py-3">
                <h6 class="text-muted small text-uppercase">Total Assets</h6>
                <h4 class="mb-0 fw-bold">{{ $assets->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body py-3">
                <h6 class="text-muted small text-uppercase">Assigned</h6>
                <h4 class="mb-0 fw-bold text-primary">{{ $assets->where('status', 'Assigned')->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body py-3">
                <h6 class="text-muted small text-uppercase">Available</h6>
                <h4 class="mb-0 fw-bold text-success">{{ $assets->where('status', 'Available')->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body py-3">
                <h6 class="text-muted small text-uppercase">In Repair</h6>
                <h4 class="mb-0 fw-bold text-warning">{{ $assets->where('status', 'Repair')->count() }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Asset Name</th>
                        <th>Type</th>
                        <th>Serial Number</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td class="ps-4">
                            <strong>{{ $asset->name }}</strong>
                        </td>
                        <td>{{ $asset->type }}</td>
                        <td><code>{{ $asset->serial_number ?? 'N/A' }}</code></td>
                        <td>
                            @if($asset->employee)
                                {{ $asset->employee->first_name }} {{ $asset->employee->last_name }}
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = match($asset->status) {
                                    'Available' => 'bg-success',
                                    'Assigned' => 'bg-primary',
                                    'Repair' => 'bg-warning text-dark',
                                    'Retired' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $asset->status }}</span>
                        </td>
                        <td>{{ $asset->condition }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this asset record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No assets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
