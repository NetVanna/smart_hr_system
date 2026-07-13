@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
    <div class="row">
        <div class="col-8">
            <h2>{{ __('messages.dashboard') }}</h2>
            <p class="text-muted">{{ __('messages.welcome_message') }}</p>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Stat Cards Example -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1">{{ __('messages.total_employees') }}</h6>
                    <h3 class="mb-0">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1">{{ __('messages.present_today') }}</h6>
                    <h3 class="mb-0">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1">{{ __('messages.on_leave') }}</h6>
                    <h3 class="mb-0">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1">{{ __('messages.departments') }}</h6>
                    <h3 class="mb-0">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row Placeholder -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('messages.attendance_overview') }}</h5>
                </div>
                <div class="card-body"
                    style="height: 300px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                    <span class="text-muted">{{ __('messages.chart_placeholder') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('messages.recent_activities') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle text-primary" style="font-size:8px;"></i>
                            {{ __('messages.system_accessed') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection