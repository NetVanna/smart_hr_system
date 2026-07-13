@extends('layouts.app')

@section('title', 'System Health')

@section('content')
<div class="mb-4">
    <h3><i class="fa-solid fa-heart-pulse me-2"></i> System Health Monitor</h3>
    <p class="text-muted">Real-time status of critical infrastructure and resources.</p>
</div>

<div class="row">
    <!-- Component Status -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <div class="mb-2"><i class="fa-solid fa-database text-primary fs-3"></i></div>
            <h6 class="text-muted small mb-1">Database</h6>
            <div class="fw-bold {{ $dbStatus == 'Healthy' ? 'text-success' : 'text-danger' }}">
                <i class="fa-solid fa-circle {{ $dbStatus == 'Healthy' ? 'text-success' : 'text-danger' }} small me-1"></i>
                {{ $dbStatus }}
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <div class="mb-2"><i class="fa-solid fa-hard-drive text-info fs-3"></i></div>
            <h6 class="text-muted small mb-1">Storage</h6>
            <div class="fw-bold {{ $storageStatus == 'Healthy' ? 'text-success' : 'text-danger' }}">
                <i class="fa-solid fa-circle {{ $storageStatus == 'Healthy' ? 'text-success' : 'text-danger' }} small me-1"></i>
                {{ $storageStatus }}
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <div class="mb-2"><i class="fa-solid fa-microchip text-warning fs-3"></i></div>
            <h6 class="text-muted small mb-1">CPU Load</h6>
            <div class="fw-bold text-dark">{{ $cpuLoad }}%</div>
            <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $cpuLoad }}%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <div class="mb-2"><i class="fa-solid fa-memory text-danger fs-3"></i></div>
            <h6 class="text-muted small mb-1">Memory Usage</h6>
            <div class="fw-bold text-dark">{{ $memoryUsage }}%</div>
            <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $memoryUsage }}%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Recent System Alerts</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2 small mb-2 border-0 bg-light">
                    <i class="fa-solid fa-info-circle me-1 text-primary"></i> Daily backup completed successfully. (2h ago)
                </div>
                <div class="alert alert-info py-2 small mb-2 border-0 bg-light">
                    <i class="fa-solid fa-info-circle me-1 text-primary"></i> Khmer localization cache cleared. (5h ago)
                </div>
                <div class="alert alert-warning py-2 small mb-2 border-0 bg-light">
                    <i class="fa-solid fa-triangle-exclamation me-1 text-warning"></i> High CPU spike detected during payroll export. (Yesterday)
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Live Traffic Monitor</h5>
            </div>
            <div class="card-body">
                <canvas id="trafficChart" style="max-height: 200px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trafficChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00'],
        datasets: [{
            label: 'Requests/min',
            data: [120, 190, 300, 250, 200, 230],
            borderColor: '#667eea',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(102, 126, 234, 0.1)'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
