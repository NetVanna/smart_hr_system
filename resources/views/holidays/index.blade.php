@extends('layouts.app')

@section('title', __('messages.public_holidays'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-calendar-star me-2"></i> {{ __('messages.welcome') }}, {{ __('messages.public_holidays') }}</h3>
    <span class="badge bg-primary px-3 py-2">Cambodia 2025</span>
</div>

<div class="row">
    @foreach($holidays as $holiday)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-light rounded p-2 text-center" style="min-width: 60px;">
                        <span class="d-block fw-bold text-primary fs-5">{{ \Carbon\Carbon::parse($holiday->date)->format('d') }}</span>
                        <small class="text-muted text-uppercase">{{ \Carbon\Carbon::parse($holiday->date)->format('M') }}</small>
                    </div>
                    <span class="badge bg-soft-primary text-primary">{{ \Carbon\Carbon::parse($holiday->date)->format('Y') }}</span>
                </div>
                <h5 class="fw-bold mb-2">
                    @if(app()->getLocale() === 'kh' && $holiday->name_kh)
                        {{ $holiday->name_kh }}
                    @else
                        {{ $holiday->name }}
                    @endif
                </h5>
                <p class="text-muted small mb-0">
                    @if(app()->getLocale() === 'kh' && $holiday->description_kh)
                        {{ $holiday->description_kh }}
                    @else
                        {{ $holiday->description }}
                    @endif
                </p>
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3">
                <small class="text-muted"><i class="fa-solid fa-clock me-1"></i> Full Day Holiday</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
    .bg-soft-primary { background-color: rgba(102, 126, 234, 0.1); }
</style>
@endsection
