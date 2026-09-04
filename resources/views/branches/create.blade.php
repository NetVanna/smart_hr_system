@extends('layouts.app')

@section('title', __('messages.create_branch'))

@section('content')
<div class="container-fluid p-0" style="max-width: 860px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ __('messages.create_branch') }}</h3>
            <p class="text-muted small mb-0">Register a new physical office or provincial branch location</p>
        </div>
        <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary btn-pill btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <div class="card card-attendee shadow-sm">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('branches.store') }}" method="POST">
                @csrf

                <!-- Basic Info -->
                <div class="mb-4">
                    <h6 class="fw-bold text-primary text-uppercase mb-3" style="font-size: 0.78rem; letter-spacing: 0.05em;">
                        <i class="fa-solid fa-building me-1"></i> Branch Details
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold" for="name">{{ __('messages.branch_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Phnom Penh Head Office or Siem Reap Branch">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold" for="code">{{ __('messages.branch_code') }}</label>
                            <input type="text" name="code" id="code" class="form-control rounded-3 @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. PP-01, SR-01">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact & Address -->
                <div class="mb-4">
                    <h6 class="fw-bold text-primary text-uppercase mb-3" style="font-size: 0.78rem; letter-spacing: 0.05em;">
                        <i class="fa-solid fa-map-location-dot me-1"></i> {{ __('messages.branch_address') }}
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold" for="phone">{{ __('messages.branch_phone') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g. 023 999 888 or 012 345 678">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold" for="geofence_radius">{{ __('messages.geofence_radius') }}</label>
                            <div class="input-group">
                                <input type="number" name="geofence_radius" id="geofence_radius" class="form-control rounded-3 @error('geofence_radius') is-invalid @enderror" value="{{ old('geofence_radius', 100) }}" min="10" max="10000" step="10">
                                <span class="input-group-text bg-light text-muted">meters</span>
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">Acceptable check-in distance around this branch pin</small>
                            @error('geofence_radius') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="address">Full Address / Street</label>
                            <textarea name="address" id="address" rows="2" class="form-control rounded-3 @error('address') is-invalid @enderror" placeholder="#123 Street 271, Sangkat Boeng Tumpun, Khan Mean Chey, Phnom Penh">{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- GPS & Geofencing -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <h6 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 0.78rem; letter-spacing: 0.05em;">
                            <i class="fa-solid fa-location-crosshairs me-1"></i> {{ __('messages.gps_coordinates') }}
                        </h6>
                        <small class="text-muted">{{ __('messages.preset_locations') }}:</small>
                    </div>

                    <!-- Cambodia Province Quick Presets -->
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2" style="font-size: 0.74rem;" onclick="setCoordinates(11.5564, 104.9282, 'Phnom Penh Central')">
                            📍 Phnom Penh (Central)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2" style="font-size: 0.74rem;" onclick="setCoordinates(11.5739, 104.8967, 'Phnom Penh Toul Kork')">
                            📍 Toul Kork
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2" style="font-size: 0.74rem;" onclick="setCoordinates(13.3633, 103.8564, 'Siem Reap Branch')">
                            📍 Siem Reap
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2" style="font-size: 0.74rem;" onclick="setCoordinates(13.0957, 103.2022, 'Battambang Branch')">
                            📍 Battambang
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2" style="font-size: 0.74rem;" onclick="setCoordinates(10.6275, 103.5221, 'Sihanoukville Branch')">
                            📍 Sihanoukville
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold" for="latitude">{{ __('messages.latitude') }}</label>
                            <input type="number" step="any" name="latitude" id="latitude" class="form-control rounded-3 @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" placeholder="e.g. 11.5564">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold" for="longitude">{{ __('messages.longitude') }}</label>
                            <input type="number" step="any" name="longitude" id="longitude" class="form-control rounded-3 @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" placeholder="e.g. 104.9282">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Checkbox -->
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold small ms-2" for="is_active">Branch Active & Open for Attendance</label>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('branches.index') }}" class="btn btn-light border btn-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-pill px-4 shadow-sm">
                        <i class="fa-solid fa-check me-1"></i> Save Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setCoordinates(lat, lng, desc) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }
</script>
@endsection
