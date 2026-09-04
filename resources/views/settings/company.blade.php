@extends('layouts.app')

@section('title', __('messages.company_settings'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fa-solid fa-gears me-2"></i> {{ __('messages.company_settings') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.company.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.company_name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ $company->name }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ $company->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.phone_number') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ $company->phone }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.address') }}</label>
                        <textarea name="address" class="form-control" rows="2">{{ $company->address }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex justify-content-between">
                            <span>{{ __('messages.office_location') }}</span>
                             <span class="text-primary small" style="cursor: pointer;" id="useCurrentLocationBtn">
                                 <i class="fa-solid fa-location-crosshairs"></i> {{ __('messages.use_current_location') }}
                             </span>
                        </label>
                        <div id="map" style="height: 300px; border-radius: 12px; border: 1px solid #ddd;"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ $company->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $company->longitude }}">
                        <small class="text-muted">{{ __('messages.map_click_hint') }}</small>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-primary mb-3"><i class="fa-solid fa-coins me-1"></i> Currency & Exchange Rate (Cambodia)</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Base Currency</label>
                            <select name="base_currency" class="form-select">
                                <option value="USD" {{ ($company->base_currency ?? 'USD') === 'USD' ? 'selected' : '' }}>USD ($ - United States Dollar)</option>
                                <option value="KHR" {{ ($company->base_currency ?? '') === 'KHR' ? 'selected' : '' }}>KHR (៛ - Khmer Riel)</option>
                            </select>
                            <small class="text-muted">Primary currency used for salary and expense calculations.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Exchange Rate (1 USD = ? KHR)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">៛</span>
                                <input type="number" step="1" name="exchange_rate" class="form-control" value="{{ $company->exchange_rate ?? 4100 }}" required>
                            </div>
                            <small class="text-muted">Standard rate in Cambodia (typically 4,100 KHR = $1 USD).</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-primary mb-3">{{ __('messages.localization_alerts') }}</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.geofence_radius') }}</label>
                            <input type="number" name="geofence_radius" class="form-control" value="{{ $company->geofence_radius }}" required>
                            <small class="text-muted">{{ __('messages.radius_hint') }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.telegram_chat_id') }}</label>
                            <div class="input-group">
                                <input type="text" name="telegram_chat_id" class="form-control" value="{{ $company->telegram_chat_id }}" placeholder="-123456789">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#tgHelperModal">
                                    <i class="fa-solid fa-circle-question"></i>
                                </button>
                            </div>
                            <small class="text-muted">{{ __('messages.telegram_hint') }}</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">{{ __('messages.save_settings') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Telegram Helper Modal -->
<div class="modal fade" id="tgHelperModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.how_to_get_tg_id') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ol class="small">
                    <li>{!! __('messages.tg_step_1') !!}</li>
                    <li>{!! __('messages.tg_step_2') !!}</li>
                    <li>{!! __('messages.tg_step_3') !!}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { z-index: 1; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let marker;

    document.addEventListener('DOMContentLoaded', function() {
        const initialLat = {{ $company->latitude ?? 11.5564 }};
        const initialLng = {{ $company->longitude ?? 104.9282 }};

        map = L.map('map').setView([initialLat, initialLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            updateLatLng(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLatLng(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('useCurrentLocationBtn').addEventListener('click', getCurrentLocation);
    });

    function updateLatLng(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        // Using Nominatim (OpenStreetMap) for free reverse geocoding
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.querySelector('textarea[name="address"]').value = data.display_name;
                }
            })
            .catch(error => console.error('Error fetching address:', error));
    }

    function getCurrentLocation() {
        if ("geolocation" in navigator) {
            const btn = document.getElementById('useCurrentLocationBtn');
            if (!btn) return;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + {!! json_encode(__('messages.locating')) !!};

            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 17);
                marker.setLatLng([lat, lng]);
                updateLatLng(lat, lng);
                btn.innerHTML = originalHtml;
            }, function(error) {
                btn.innerHTML = originalHtml;
                let errorMsg = {!! json_encode(__('messages.loc_error')) !!};
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg = {!! json_encode(__('messages.loc_denied')) !!};
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg = {!! json_encode(__('messages.loc_unavailable')) !!};
                        break;
                    case error.TIMEOUT:
                        errorMsg = {!! json_encode(__('messages.loc_timeout')) !!};
                        break;
                }
                alert(errorMsg);
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        } else {
            alert({!! json_encode(__('messages.loc_not_supported')) !!});
        }
    }
</script>
@endpush
@endsection
