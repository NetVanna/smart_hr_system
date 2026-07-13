@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="row mb-4 row-cols-1 row-cols-md-3 row-cols-xl-5 g-3">
    <div class="col">
        <div class="card bg-primary text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase opacity-75 small">MRR</h6>
                <h3 class="fw-bold mb-1">${{ number_format($mrr, 2) }}</h3>
                <p class="mb-0 small"><i class="fa-solid fa-chart-line"></i> Total monthly</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase opacity-75 small">Companies</h6>
                <h3 class="fw-bold mb-1">{{ $totalCompanies }}</h3>
                <p class="mb-0 small"><i class="fa-solid fa-building"></i> Platform total</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-info text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase opacity-75 small">Paid Subs</h6>
                <h3 class="fw-bold mb-1">{{ $paidCompanies }}</h3>
                <p class="mb-0 small"><i class="fa-solid fa-check-circle"></i> Active paid</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-warning text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase opacity-75 small">Trial Users</h6>
                <h3 class="fw-bold mb-1">{{ $trialCompanies }}</h3>
                <p class="mb-0 small"><i class="fa-solid fa-hourglass-half"></i> In onboarding</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card bg-danger text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="card-title text-uppercase opacity-75 small">Churn Rate</h6>
                <h3 class="fw-bold mb-1">{{ number_format($churnRate, 1) }}%</h3>
                <p class="mb-0 small"><i class="fa-solid fa-user-slash"></i> Retention loss</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Registration Growth (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="growthChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Subscription Status</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($growthData->pluck('month')) !!},
            datasets: [{
                label: 'New Registrations',
                data: {!! json_encode($growthData->pluck('count')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($subscriptionStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($subscriptionStatus->pluck('total')) !!},
                backgroundColor: ['#198754', '#ffc107', '#dc3545', '#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
