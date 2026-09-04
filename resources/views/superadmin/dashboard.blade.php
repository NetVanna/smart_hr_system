@extends('layouts.app')

@section('title', __('messages.superadmin_dashboard') ?? 'Super Admin Control Center')

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Header Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                    &#x1F1F0;&#x1F1ED; Cambodia Multi-Tenant SaaS
                </span>
                <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                    1 USD = 4,100 KHR
                </span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ __('messages.superadmin_dashboard') ?? 'Platform Control Center' }}</h3>
            <p class="text-muted small mb-0">{{ __('messages.superadmin_subtitle') ?? 'Real-time multi-tenant monitoring, billing & support operations across Cambodia.' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 position-relative">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>{{ __('messages.subscriptions_billing') ?? 'Billing & Invoices' }}</span>
                @if($pendingApprovals->count() > 0)
                    <span class="badge bg-danger rounded-pill">{{ $pendingApprovals->count() }}</span>
                @endif
            </a>
            <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('messages.add_company') ?? 'Add Company' }}</span>
            </a>
        </div>
    </div>

    <!-- Alert for Pending KHQR Payment Approvals -->
    @if($pendingApprovals->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background-color: #fef3c7; border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 44px; height: 44px; flex-shrink: 0;">
                <i class="fa-solid fa-qrcode text-warning fa-lg"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">
                    {{ $pendingApprovals->count() }} {{ __('messages.pending_khqr_payments') ?? 'Pending KHQR / Bank Transfer Approvals' }}
                </h6>
                <p class="mb-0 text-muted small">
                    {{ __('messages.pending_khqr_desc') ?? 'Clients have uploaded bank payment slips (ABA / Bakong / Wing) awaiting verification.' }}
                </p>
            </div>
        </div>
        <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-warning btn-sm rounded-pill px-4 py-2 fw-bold text-dark shadow-sm">
            {{ __('messages.review_slips') ?? 'Review Payment Slips' }} <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>
    @endif

    <!-- 5 KPI Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-5 g-3">
        <!-- MRR (USD & KHR) -->
        <div class="col">
            <div class="card card-attendee h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Monthly Recurring (MRR)</span>
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-dollar-sign"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-0">${{ number_format($mrr, 2) }}</h4>
                <div class="text-muted small mt-1" style="font-size: 0.76rem;">
                    &#x1F1F0;&#x1F1ED; ៛ {{ number_format($mrr_khr, 0) }} KHR
                </div>
            </div>
        </div>

        <!-- Total Companies -->
        <div class="col">
            <div class="card card-attendee h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Tenants</span>
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $totalCompanies }}</h4>
                <div class="text-success small mt-1" style="font-size: 0.76rem;">
                    <i class="fa-solid fa-circle-check"></i> {{ $paidCompanies }} {{ __('messages.active') }} • {{ $trialCompanies }} {{ __('messages.trial') }}
                </div>
            </div>
        </div>

        <!-- Provincial Branches -->
        <div class="col">
            <div class="card card-attendee h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Cambodia Branches</span>
                    <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $totalBranches }}</h4>
                <div class="text-muted small mt-1" style="font-size: 0.76rem;">
                    GPS Geofenced Across Provinces
                </div>
            </div>
        </div>

        <!-- Total Staff Managed -->
        <div class="col">
            <div class="card card-attendee h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Active Staff</span>
                    <div class="rounded-circle bg-secondary-subtle text-dark d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $totalEmployees }}</h4>
                <div class="text-muted small mt-1" style="font-size: 0.76rem;">
                    Under SaaS Attendance
                </div>
            </div>
        </div>

        <!-- Churn / Retention -->
        <div class="col">
            <div class="card card-attendee h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Retention Loss</span>
                    <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ number_format($churnRate, 1) }}%</h4>
                <div class="text-muted small mt-1" style="font-size: 0.76rem;">
                    Platform Churn Rate
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Charts & Tables -->
    <div class="row g-4">
        <!-- Left Side (8 cols) -->
        <div class="col-lg-8 d-flex flex-column gap-4">
            <!-- Registration Growth Chart -->
            <div class="card card-attendee p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">{{ __('messages.registration_growth') ?? 'New Company Registrations (Last 6 Months)' }}</h6>
                        <small class="text-muted">Monthly onboarding velocity across Cambodian businesses</small>
                    </div>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1">Monthly</span>
                </div>
                <div style="height: 220px;">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Recent Companies Registered -->
            <div class="card card-attendee">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">{{ __('messages.recent_tenants') ?? 'Recent Registered Tenants' }}</h6>
                    <a href="{{ route('superadmin.companies.index') }}" class="btn btn-sm btn-light text-primary rounded-pill px-3">
                        {{ __('messages.view_all') ?? 'View All' }} <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-attendee align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">{{ __('messages.company') }}</th>
                                <th>{{ __('messages.admin') }}</th>
                                <th>{{ __('messages.branches') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th class="text-end pe-3">{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCompanies as $comp)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">{{ $comp->name }}</div>
                                    <small class="text-muted">{{ $comp->email }}</small>
                                </td>
                                <td>
                                    @php $admin = $comp->users->first(); @endphp
                                    @if($admin)
                                        <div class="small fw-semibold text-dark">{{ $admin->name }}</div>
                                        @if($admin->telegram_username)
                                            <a href="https://t.me/{{ $admin->telegram_username }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none">
                                                <i class="fa-brands fa-telegram"></i> @ {{ $admin->telegram_username }}
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                        <i class="fa-solid fa-building me-1 text-primary"></i> {{ $comp->branches_count }}
                                    </span>
                                </td>
                                <td>
                                    @if($comp->subscription_status === 'Active')
                                        <span class="badge badge-attendee-active px-2 py-1">{{ __('messages.active') }}</span>
                                    @elseif($comp->subscription_status === 'Trial')
                                        <span class="badge badge-attendee-pending px-2 py-1">{{ __('messages.trial') }}</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ $comp->subscription_status }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('superadmin.companies.impersonate', $comp) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 shadow-sm" title="Support Login">
                                        <i class="fa-solid fa-user-shield me-1"></i> {{ __('messages.support_login') ?? 'Support Login' }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No tenants registered yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side (4 cols) -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <!-- Subscription Distribution Doughnut -->
            <div class="card card-attendee p-4">
                <h6 class="fw-bold text-dark mb-1">{{ __('messages.subscription_breakdown') ?? 'Subscription Breakdown' }}</h6>
                <small class="text-muted mb-3 d-block">Tenant active status distribution</small>
                <div style="height: 180px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Open Support Tickets -->
            <div class="card card-attendee p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ __('messages.support_tickets') ?? 'Support Tickets' }}</h6>
                        <small class="text-muted">{{ $openTickets->count() }} active requests</small>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light text-primary rounded-pill px-3">
                        {{ __('messages.view_all') ?? 'View' }}
                    </a>
                </div>

                <div class="d-flex flex-column gap-2">
                    @forelse($openTickets as $ticket)
                    <div class="p-2 rounded-3 bg-light border d-flex flex-column gap-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-bold text-dark text-truncate" style="font-size: 0.82rem; max-width: 170px;">
                                {{ $ticket->subject }}
                            </span>
                            @if($ticket->priority === 'High')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.68rem;">High</span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size: 0.68rem;">{{ $ticket->priority }}</span>
                            @endif
                        </div>
                        <small class="text-muted text-truncate" style="font-size: 0.76rem;">
                            <i class="fa-regular fa-building me-1"></i> {{ $ticket->company?->name }} • {{ $ticket->created_at->diffForHumans() }}
                        </small>
                    </div>
                    @empty
                    <div class="text-center py-3 text-muted small">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> All support tickets resolved!
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Cambodia Provincial Coverage Card -->
            <div class="card card-attendee p-3 bg-primary text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fa-solid fa-location-crosshairs fa-lg text-warning"></i>
                    <h6 class="fw-bold mb-0">Cambodia Coverage</h6>
                </div>
                <p class="small mb-3 opacity-75">Multi-branch and geofence tracking live across all active provinces.</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-primary rounded-pill px-2 py-1" style="font-size: 0.72rem;">Phnom Penh Central</span>
                    <span class="badge bg-white text-primary rounded-pill px-2 py-1" style="font-size: 0.72rem;">Toul Kork</span>
                    <span class="badge bg-white text-primary rounded-pill px-2 py-1" style="font-size: 0.72rem;">Siem Reap</span>
                    <span class="badge bg-white text-primary rounded-pill px-2 py-1" style="font-size: 0.72rem;">Battambang</span>
                    <span class="badge bg-white text-primary rounded-pill px-2 py-1" style="font-size: 0.72rem;">Sihanoukville</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Registration Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($growthData->pluck('month')) !!},
            datasets: [{
                label: 'New Tenants',
                data: {!! json_encode($growthData->pluck('count')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, color: '#94a3b8' },
                    grid: { color: 'rgba(226, 232, 240, 0.6)' }
                },
                x: {
                    ticks: { color: '#94a3b8' },
                    grid: { display: false }
                }
            }
        }
    });

    // Subscription Status Doughnut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($subscriptionStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($subscriptionStatus->pluck('total')) !!},
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#2563eb', '#64748b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            cutout: '68%'
        }
    });
</script>
@endpush
@endsection
