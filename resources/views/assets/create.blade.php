@extends('layouts.app')

@section('title', 'Add New Asset')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fa-solid fa-plus me-2"></i> Add New Asset</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('assets.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Asset Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. MacBook Pro M3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Asset Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Laptop">Laptop</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Phone">Mobile Phone</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assigned To (Employee)</label>
                            <select name="employee_id" class="form-select">
                                <option value="">None (Available)</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Condition</label>
                            <select name="condition" class="form-select" required>
                                <option value="New">New</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Available">Available</option>
                                <option value="Assigned">Assigned</option>
                                <option value="Repair">Under Repair</option>
                                <option value="Retired">Retired</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Purchased At</label>
                            <input type="date" name="purchased_at" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" step="0.01" name="price" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('assets.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
