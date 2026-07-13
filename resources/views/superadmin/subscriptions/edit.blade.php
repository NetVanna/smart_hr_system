@extends('layouts.app')

@section('title', 'Edit Subscription')

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('superadmin.subscriptions.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> Subscriptions</a> / Edit</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('superadmin.subscriptions.update', $subscription) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Company</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-building"></i></span>
                    <input type="text" class="form-control" value="{{ optional($subscription->company)->name }}" disabled>
                </div>
                <input type="hidden" name="company_id" value="{{ $subscription->company_id }}">
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Plan <span class="text-danger">*</span></label>
                    <select name="plan" class="form-select @error('plan') is-invalid @enderror" required>
                        <option value="Basic" {{ old('plan', $subscription->plan) == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Pro" {{ old('plan', $subscription->plan) == 'Pro' ? 'selected' : '' }}>Pro</option>
                        <option value="Enterprise" {{ old('plan', $subscription->plan) == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (USD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $subscription->price) }}" required>
                    </div>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $subscription->start_date) }}" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $subscription->end_date) }}" required>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Receipt</label>
                @if($subscription->receipt_path)
                    <div class="mb-2">
                        <a href="{{ Storage::url($subscription->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="fa-solid fa-eye"></i> View Current Receipt
                        </a>
                    </div>
                @endif
                <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror">
                <div class="form-text small">Upload a new receipt to replace the current one (JPEG, PNG, JPG).</div>
                @error('receipt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="Active" {{ old('status', $subscription->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Pending Approval" {{ old('status', $subscription->status) == 'Pending Approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="Expired" {{ old('status', $subscription->status) == 'Expired' ? 'selected' : '' }}>Expired</option>
                    <option value="Approved" {{ old('status', $subscription->status) == 'Approved' ? 'selected' : '' }}>Approved (Mark as Active)</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Subscription</button>
            <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
