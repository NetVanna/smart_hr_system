@extends('layouts.app')

@section('title', 'Edit Asset')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fa-solid fa-edit me-2"></i> Edit Asset: {{ $asset->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Asset Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $asset->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Asset Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Laptop" {{ $asset->type === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Desktop" {{ $asset->type === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="Phone" {{ $asset->type === 'Phone' ? 'selected' : '' }}>Mobile Phone</option>
                                <option value="Tablet" {{ $asset->type === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Monitor" {{ $asset->type === 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="Other" {{ $asset->type === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ $asset->serial_number }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assigned To (Employee)</label>
                            <select name="employee_id" class="form-select">
                                <option value="">None (Available)</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $asset->employee_id == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Condition</label>
                            <select name="condition" class="form-select" required>
                                <option value="New" {{ $asset->condition === 'New' ? 'selected' : '' }}>New</option>
                                <option value="Good" {{ $asset->condition === 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Fair" {{ $asset->condition === 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option value="Poor" {{ $asset->condition === 'Poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Available" {{ $asset->status === 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Assigned" {{ $asset->status === 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="Repair" {{ $asset->status === 'Repair' ? 'selected' : '' }}>Under Repair</option>
                                <option value="Retired" {{ $asset->status === 'Retired' ? 'selected' : '' }}>Retired</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Purchased At</label>
                            <input type="date" name="purchased_at" class="form-control" value="{{ $asset->purchased_at }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $asset->price }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('assets.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-history me-2 text-primary"></i> Assignment History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Action</th>
                                <th>Employee</th>
                                <th>Condition</th>
                                <th>Date</th>
                                <th class="pe-3">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asset->logs->sortByDesc('created_at') as $log)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge {{ $log->action === 'Checkout' ? 'bg-primary' : 'bg-success' }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>{{ $log->employee->first_name }} {{ $log->employee->last_name }}</td>
                                <td>{{ $log->condition }}</td>
                                <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                <td class="pe-3 text-muted">{{ $log->notes }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No history found for this asset.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
