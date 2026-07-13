@extends('layouts.app')

@section('title', 'Add Subscription')

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('superadmin.subscriptions.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> Subscriptions</a> / New</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('superadmin.subscriptions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Company <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-building"></i></span>
                    <select name="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Plan <span class="text-danger">*</span></label>
                    <select name="plan" class="form-select @error('plan') is-invalid @enderror" required>
                        <option value="Basic" {{ old('plan') == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Pro" {{ old('plan') == 'Pro' ? 'selected' : '' }}>Pro</option>
                        <option value="Enterprise" {{ old('plan') == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (USD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" required>
                    </div>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y-m-d', strtotime('+1 year'))) }}" required>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Receipt</label>
                <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror">
                <div class="form-text small">Upload payment proof (JPEG, PNG, JPG).</div>
                @error('receipt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Pending Approval" {{ old('status') == 'Pending Approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="Expired" {{ old('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved (Mark as Active)</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Subscription</button>
            <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
