@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('superadmin.companies.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> Companies</a> / Edit</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('superadmin.companies.update', $company) }}" method="POST">
            @csrf
            @method('PUT')
            
            <h5 class="mb-3 border-bottom pb-2">Company Information</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $company->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $company->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $company->address) }}">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Localization Settings</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Base Currency</label>
                    <select name="base_currency" class="form-select @error('base_currency') is-invalid @enderror">
                        <option value="USD" {{ old('base_currency', $company->base_currency) == 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                        <option value="KHR" {{ old('base_currency', $company->base_currency) == 'KHR' ? 'selected' : '' }}>KHR (Khmer Riel)</option>
                    </select>
                    @error('base_currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Exchange Rate (1 USD to KHR)</label>
                    <input type="number" name="exchange_rate" class="form-control @error('exchange_rate') is-invalid @enderror" value="{{ old('exchange_rate', $company->exchange_rate) }}" step="0.01">
                    @error('exchange_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Geofencing & GPS</h5>
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $company->latitude) }}" placeholder="e.g. 11.5564">
                    @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $company->longitude) }}" placeholder="e.g. 104.9282">
                    @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Radius (Meters)</label>
                    <input type="number" name="geofence_radius" class="form-control @error('geofence_radius') is-invalid @enderror" value="{{ old('geofence_radius', $company->geofence_radius) }}">
                    @error('geofence_radius') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Subscription Details</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Plan <span class="text-danger">*</span></label>
                    <select name="subscription_plan" class="form-select @error('subscription_plan') is-invalid @enderror" required>
                        <option value="Basic" {{ old('subscription_plan', $company->subscription_plan) == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Pro" {{ old('subscription_plan', $company->subscription_plan) == 'Pro' ? 'selected' : '' }}>Pro</option>
                        <option value="Enterprise" {{ old('subscription_plan', $company->subscription_plan) == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('subscription_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="subscription_status" class="form-select @error('subscription_status') is-invalid @enderror" required>
                        <option value="Trial" {{ old('subscription_status', $company->subscription_status) == 'Trial' ? 'selected' : '' }}>Trial</option>
                        <option value="Active" {{ old('subscription_status', $company->subscription_status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('subscription_status', $company->subscription_status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Expired" {{ old('subscription_status', $company->subscription_status) == 'Expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @error('subscription_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Company</button>
            <a href="{{ route('superadmin.companies.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
