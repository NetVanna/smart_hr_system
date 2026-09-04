@extends('layouts.app')

@section('title', __('messages.subscription_billing') ?? 'Subscription & Billing')

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                    <i class="fa-solid fa-credit-card me-1"></i> {{ __('messages.billing_overview') ?? 'Billing Overview' }}
                </span>
                <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                    1 USD = {{ number_format($company->exchange_rate ?? 4100, 0) }} KHR
                </span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ __('messages.subscription_billing') ?? 'Subscription & Billing' }}</h3>
            <p class="text-muted small mb-0">{{ __('messages.billing_subtitle') ?? 'Manage your company subscription plan, view employee quota limits, and submit ABA / Bakong KHQR renewal slips.' }}</p>
        </div>

        <div>
            <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 py-2 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                <i class="fa-solid fa-arrow-up-right-dots"></i>
                <span>{{ __('messages.upgrade_renew_plan') ?? 'Renew or Upgrade Plan' }}</span>
            </button>
        </div>
    </div>

    <!-- Active Plan & Quota Card -->
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card card-attendee h-100 p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Current Workspace Plan</span>
                        <h2 class="fw-bold text-dark mb-0 mt-1">
                            {{ $company->subscription_plan ?? 'Basic' }}
                        </h2>
                    </div>
                    <div>
                        @if($company->subscription_status === 'Active')
                            <span class="badge badge-attendee-active px-3 py-2 fs-6">
                                <i class="fa-solid fa-circle-check me-1"></i> Active
                            </span>
                        @elseif($company->subscription_status === 'Pending')
                            <span class="badge badge-attendee-pending px-3 py-2 fs-6">
                                <i class="fa-solid fa-clock me-1"></i> Pending Approval
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-6 rounded-pill">
                                <i class="fa-solid fa-hourglass-half me-1"></i> Trial / Setup
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Expiration date -->
                <div class="d-flex align-items-center gap-2 text-muted small mb-4">
                    <i class="fa-regular fa-calendar text-primary"></i>
                    <span>
                        Billing Period: 
                        <strong>{{ $activeSubscription ? \Carbon\Carbon::parse($activeSubscription->start_date)->format('d M Y') : 'Active' }}</strong> 
                        to 
                        <strong>{{ $activeSubscription ? \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M Y') : '14-Day Free Trial' }}</strong>
                    </span>
                </div>

                <!-- Quota progress -->
                <div class="bg-light p-3 rounded-4 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold text-dark">
                            <i class="fa-solid fa-users text-primary me-1"></i> Employee Quota Usage
                        </span>
                        <span class="small fw-semibold text-muted">
                            <strong class="text-dark">{{ $employeeCount }}</strong> / {{ $maxEmployees }} staff ({{ $usagePercent }}%)
                        </span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar {{ $usagePercent >= 90 ? 'bg-danger' : ($usagePercent >= 70 ? 'bg-warning' : 'bg-primary') }}" role="progressbar" style="width: {{ $usagePercent }}%;" aria-valuenow="{{ $usagePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted mt-2 d-block" style="font-size: 0.74rem;">
                        @if($usagePercent >= 90)
                            ⚠️ You are nearing your plan's staff limit. Upgrade to a higher tier to add more employees without interruption.
                        @else
                            You can add up to {{ max(0, $maxEmployees - $employeeCount) }} more staff members under this tier.
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Cambodia KHQR Payment Info Box -->
        <div class="col-12 col-lg-5">
            <div class="card card-attendee h-100 p-4" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: white;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger text-white rounded-pill px-2 py-1 fw-bold" style="font-size: 0.72rem;">BAKONG / ABA</span>
                        <span class="small text-white-50">Instant KHQR</span>
                    </div>
                    <i class="fa-solid fa-qrcode fa-2x text-white-50"></i>
                </div>
                <h5 class="fw-bold text-white mb-1">Direct Bank Transfer</h5>
                <p class="text-white-50 small mb-3">Renew your subscription directly via ABA Mobile or any Bakong-enabled Cambodian banking app.</p>

                <div class="bg-white bg-opacity-10 p-3 rounded-3 mb-3 border border-white border-opacity-10">
                    <div class="d-flex justify-content-between text-white-50 small mb-1">
                        <span>Account Name:</span>
                        <strong class="text-white">SMART HR SYSTEMS CO., LTD.</strong>
                    </div>
                    <div class="d-flex justify-content-between text-white-50 small mb-1">
                        <span>ABA Account (USD):</span>
                        <strong class="text-white">001 888 999</strong>
                    </div>
                    <div class="d-flex justify-content-between text-white-50 small">
                        <span>Bakong Phone ID:</span>
                        <strong class="text-white">012 888 999</strong>
                    </div>
                </div>

                <button type="button" class="btn btn-primary rounded-pill w-100 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                    <i class="fa-solid fa-upload me-1"></i> Submit Payment Receipt Slip
                </button>
            </div>
        </div>
    </div>

    <!-- Available Plans Comparison -->
    <div class="card card-attendee">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-0 text-dark">Available SaaS Plans</h5>
            <p class="text-muted small mb-0">Choose the right tier for your organization's workforce size in Cambodia.</p>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                @foreach($plans as $p)
                <div class="col-12 col-md-4">
                    <div class="card h-100 border rounded-4 p-4 d-flex flex-column justify-content-between {{ $p['is_current'] ? 'border-primary shadow-sm' : '' }}" style="{{ $p['is_current'] ? 'background-color: #f8fafc;' : '' }}">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold text-dark mb-0">{{ $p['name'] }}</h5>
                                @if($p['is_current'])
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-1">Current</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-baseline gap-1 my-3">
                                <span class="fs-3 fw-bold text-dark">${{ $p['price_usd'] }}</span>
                                <span class="text-muted small">/ year</span>
                                <span class="text-muted small ms-2">(៛{{ number_format($p['price_khr'], 0) }})</span>
                            </div>
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1 mb-3 fw-semibold">
                                <i class="fa-solid fa-user-group me-1 text-primary"></i> {{ $p['limit'] }}
                            </span>
                            <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-4">
                                @foreach($p['features'] as $feat)
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                    <span>{{ $feat }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            @if($p['is_current'])
                                <button type="button" class="btn btn-outline-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#upgradeModal" data-plan="{{ $p['name'] }}">
                                    Renew {{ $p['name'] }}
                                </button>
                            @else
                                <button type="button" class="btn btn-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#upgradeModal" data-plan="{{ $p['name'] }}">
                                    Switch to {{ $p['name'] }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Subscription Invoices History -->
    <div class="card card-attendee">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-0 text-dark">Payment & Invoice History</h5>
            <p class="text-muted small mb-0">Record of all payments submitted and verified by SuperAdmin.</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-attendee align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Reference #</th>
                            <th>Plan</th>
                            <th>Amount (USD / KHR)</th>
                            <th>Billing Period</th>
                            <th>Slip Proof</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">SUB-{{ str_pad($sub->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                    {{ $sub->plan }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">${{ number_format($sub->price, 2) }}</div>
                                <div class="text-muted small" style="font-size: 0.74rem;">៛ {{ number_format($sub->price * 4100, 0) }} KHR</div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</div>
                                <small class="text-muted">to {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</small>
                            </td>
                            <td>
                                @if($sub->receipt_path)
                                    <a href="{{ asset('storage/' . $sub->receipt_path) }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none rounded-pill px-2 py-1">
                                        <i class="fa-solid fa-file-image me-1"></i> View Slip
                                    </a>
                                @else
                                    <small class="text-muted">System Activated</small>
                                @endif
                            </td>
                            <td>
                                @if($sub->status === 'Active' || $sub->status === 'Approved')
                                    <span class="badge badge-attendee-active px-3 py-1">Active</span>
                                @elseif($sub->status === 'Pending Approval')
                                    <span class="badge badge-attendee-pending px-3 py-1">
                                        <i class="fa-solid fa-clock me-1"></i> Pending Verification
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-1">{{ $sub->status }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end text-muted small">
                                {{ $sub->created_at ? $sub->created_at->format('d M Y, H:i') : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No previous payment records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upgrade / Payment Slip Upload Modal -->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 px-4 py-3">
                <div>
                    <h6 class="modal-title fw-bold text-dark" id="upgradeModalLabel">Upload ABA / Bakong KHQR Slip</h6>
                    <small class="text-muted">Submit proof of payment to renew or upgrade your SaaS plan.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('company.billing.submitProof') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Select Desired Plan</label>
                        <select name="plan" id="modalPlanSelect" class="form-select" required>
                            <option value="Growth" {{ ($company->subscription_plan ?? '') === 'Growth' ? 'selected' : '' }}>Growth Plan ($79 / year • Up to 50 staff)</option>
                            <option value="Enterprise" {{ ($company->subscription_plan ?? '') === 'Enterprise' ? 'selected' : '' }}>Enterprise Plan ($149 / year • Unlimited staff)</option>
                            <option value="Basic" {{ ($company->subscription_plan ?? '') === 'Basic' ? 'selected' : '' }}>Basic Plan ($19 / year • Up to 10 staff)</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-3 text-center">
                        <div class="small fw-semibold text-muted mb-2">Scan with ABA Mobile or any Bakong App:</div>
                        <div class="d-inline-flex flex-column align-items-center p-3 bg-white rounded border">
                            <i class="fa-solid fa-qrcode fa-3x text-primary mb-2"></i>
                            <span class="small fw-bold text-dark">KHQR Payment</span>
                            <small class="text-muted">001 888 999 (ABA Bank)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Upload Payment Screenshot / Transfer Slip <span class="text-danger">*</span></label>
                        <input type="file" name="receipt" class="form-control" accept="image/*" required>
                        <small class="text-muted" style="font-size: 0.74rem;">Accepted: JPG, PNG, WEBP (Max 5MB)</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="fa-solid fa-upload me-1"></i> Submit Payment Proof
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
