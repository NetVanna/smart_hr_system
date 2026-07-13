@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Add Company')

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('superadmin.companies.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> Companies</a> / Add</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('superadmin.companies.store') }}" method="POST">
            @csrf
            
            <h5 class="mb-3 border-bottom pb-2">Company Information</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Localization Settings</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Base Currency</label>
                    <select name="base_currency" class="form-select @error('base_currency') is-invalid @enderror">
                        <option value="USD" {{ old('base_currency') == 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                        <option value="KHR" {{ old('base_currency') == 'KHR' ? 'selected' : '' }}>KHR (Khmer Riel)</option>
                    </select>
                    @error('base_currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Exchange Rate (1 USD to KHR)</label>
                    <input type="number" name="exchange_rate" class="form-control @error('exchange_rate') is-invalid @enderror" value="{{ old('exchange_rate', 4100) }}" step="0.01">
                    @error('exchange_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Geofencing & GPS</h5>
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" placeholder="e.g. 11.5564">
                    @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" placeholder="e.g. 104.9282">
                    @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Radius (Meters)</label>
                    <input type="number" name="geofence_radius" class="form-control @error('geofence_radius') is-invalid @enderror" value="{{ old('geofence_radius', 100) }}">
                    @error('geofence_radius') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Subscription Details</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Plan <span class="text-danger">*</span></label>
                    <select name="subscription_plan" class="form-select @error('subscription_plan') is-invalid @enderror" required>
                        <option value="Basic" {{ old('subscription_plan') == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Pro" {{ old('subscription_plan') == 'Pro' ? 'selected' : '' }}>Pro</option>
                        <option value="Enterprise" {{ old('subscription_plan') == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('subscription_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="subscription_status" class="form-select @error('subscription_status') is-invalid @enderror" required>
                        <option value="Trial" {{ old('subscription_status') == 'Trial' ? 'selected' : '' }}>Trial</option>
                        <option value="Active" {{ old('subscription_status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('subscription_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Expired" {{ old('subscription_status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @error('subscription_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Admin Account Details</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Admin Name <span class="text-danger">*</span></label>
                    <input type="text" name="admin_name" class="form-control @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}" required placeholder="e.g. John Doe">
                    @error('admin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Admin Email <span class="text-danger">*</span></label>
                    <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror" value="{{ old('admin_email') }}" required placeholder="e.g. admin@company.com">
                    @error('admin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Admin Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="admin_password" id="admin_password" class="form-control @error('admin_password') is-invalid @enderror" value="{{ old('admin_password', Str::random(10)) }}" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('admin_password').value = Math.random().toString(36).slice(-10)">Generate</button>
                    </div>
                    @error('admin_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telegram Username</label>
                    <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input type="text" name="telegram_username" class="form-control @error('telegram_username') is-invalid @enderror" value="{{ old('telegram_username') }}" placeholder="username">
                    </div>
                    @error('telegram_username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus-circle"></i> Add Company</button>
            <a href="{{ route('superadmin.companies.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
