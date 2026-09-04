@extends('layouts.app')

@section('title', __('messages.subscriptions_billing') ?? 'Subscriptions & Billing')

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                    <i class="fa-solid fa-file-invoice-dollar me-1"></i> Billing Center
                </span>
                <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                    1 USD = 4,100 KHR
                </span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ __('messages.subscriptions_billing') ?? 'Subscriptions & Billing' }}</h3>
            <p class="text-muted small mb-0">Review ABA / Bakong KHQR transfer receipts, manage plans, and activate tenant workspaces.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.subscriptions.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('messages.new_subscription') ?? 'Record Subscription' }}</span>
            </a>
        </div>
    </div>

    <!-- Pending Approval Alert -->
    @php $pendingCount = $subscriptions->where('status', 'Pending Approval')->count(); @endphp
    @if($pendingCount > 0)
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background-color: #fef3c7; border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 44px; height: 44px; flex-shrink: 0;">
                <i class="fa-solid fa-hourglass-half text-warning fa-lg"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">
                    <strong>{{ $pendingCount }}</strong> payment proof(s) awaiting your verification
                </h6>
                <p class="mb-0 text-muted small">Please inspect the transferred amount against the bank receipt and approve to activate client accounts.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Subscriptions Card -->
    <div class="card card-attendee">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-attendee align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Company</th>
                            <th>Admin & Telegram</th>
                            <th>Plan</th>
                            <th>Amount (USD / KHR)</th>
                            <th>Billing Period</th>
                            <th>Payment Slip</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                        <tr class="{{ $sub->status === 'Pending Approval' ? 'bg-warning-subtle' : '' }}">
                            <!-- Company -->
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ optional($sub->company)->name ?? 'N/A' }}</div>
                                <small class="text-muted">Tenant #{{ $sub->company_id }}</small>
                            </td>

                            <!-- Admin / Telegram -->
                            <td>
                                @php $admin = $sub->company?->users?->where('role', 'Company Admin')->first(); @endphp
                                @if($admin)
                                    <div class="small fw-semibold text-dark">{{ $admin->name }}</div>
                                    @if($admin->telegram_username)
                                        <a href="https://t.me/{{ $admin->telegram_username }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none mt-1" style="font-size: 0.72rem;">
                                            <i class="fa-brands fa-telegram me-1"></i> @ {{ $admin->telegram_username }}
                                        </a>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>

                            <!-- Plan -->
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                    {{ $sub->plan }}
                                </span>
                            </td>

                            <!-- Amount (USD & KHR) -->
                            <td>
                                <div class="fw-bold text-dark">${{ number_format($sub->price, 2) }}</div>
                                <div class="text-muted small" style="font-size: 0.74rem;">
                                    ៛ {{ number_format($sub->price * 4100, 0) }} KHR
                                </div>
                            </td>

                            <!-- Billing Period -->
                            <td>
                                <div class="small fw-semibold text-dark">{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</div>
                                <small class="text-muted">to {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</small>
                            </td>

                            <!-- Receipt Slip Button -->
                            <td>
                                @if($sub->receipt_path)
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $sub->id }}">
                                        <i class="fa-solid fa-receipt me-1"></i> View Slip
                                    </button>
                                @else
                                    <span class="text-muted small">No Receipt</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @if($sub->status === 'Active' || $sub->status === 'Approved')
                                    <span class="badge badge-attendee-active px-3 py-1">Active</span>
                                @elseif($sub->status === 'Pending Approval')
                                    <span class="badge badge-attendee-pending px-3 py-1">
                                        <i class="fa-solid fa-clock me-1"></i> Pending Approval
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-1">{{ $sub->status }}</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="pe-4 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    @if($sub->status === 'Pending Approval')
                                        <a href="{{ route('superadmin.subscriptions.approve', $sub) }}" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-semibold shadow-sm">
                                            <i class="fa-solid fa-circle-check me-1"></i> Approve
                                        </a>
                                    @endif

                                    <a href="{{ route('superadmin.subscriptions.edit', $sub) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Edit">
                                        <i class="fa-solid fa-pen" style="font-size: 0.8rem;"></i>
                                    </a>

                                    <form action="{{ route('superadmin.subscriptions.destroy', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subscription record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Delete">
                                            <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No subscription records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Render Modals Outside of Table Container to avoid Bootstrap stacking context bugs -->
@foreach($subscriptions as $sub)
@if($sub->receipt_path)
@php $admin = $sub->company?->users?->where('role', 'Company Admin')->first(); @endphp
<div class="modal fade" id="receiptModal{{ $sub->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $sub->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 px-4 py-3">
                <div>
                    <h6 class="modal-title fw-bold mb-0 text-dark" id="receiptModalLabel{{ $sub->id }}">KHQR / Bank Transfer Slip</h6>
                    <small class="text-muted">{{ optional($sub->company)->name }} • ${{ number_format($sub->price, 2) }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Plan: <strong>{{ $sub->plan }}</strong></span>
                        <span>Amount: <strong class="text-primary">${{ number_format($sub->price, 2) }} (៛{{ number_format($sub->price * 4100, 0) }})</strong></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center p-4 bg-white rounded border" style="min-height: 180px;">
                        <div class="d-flex flex-column align-items-center text-muted">
                            <i class="fa-solid fa-qrcode fa-3x text-primary mb-2"></i>
                            <span class="fw-bold text-dark">Bakong / ABA KHQR Transfer Verified</span>
                            <small class="text-muted">{{ $sub->receipt_path }}</small>
                        </div>
                    </div>
                </div>

                <!-- Copyable Telegram Notification Template -->
                <div class="text-start bg-light p-3 rounded-3 border">
                    <label class="form-label small fw-bold text-dark mb-1">
                        <i class="fa-brands fa-telegram text-info me-1"></i> Telegram Confirmation Template:
                    </label>
                    <textarea id="tgMsg{{ $sub->id }}" class="form-control form-control-sm bg-white" rows="3" readonly style="font-size: 0.78rem;">សួស្តី {{ $admin->name ?? 'Admin' }}! ការទូទាត់សម្រាប់ {{ optional($sub->company)->name }} ({{ $sub->plan }} - ${{ number_format($sub->price, 2) }}) ត្រូវបានផ្ទៀងផ្ទាត់ និងធ្វើឱ្យសកម្មដោយជោគជ័យ។ សូមអរគុណដែលបានប្រើប្រាស់ HR Attendee!

Hello {{ $admin->name ?? 'Admin' }}! Your subscription payment for {{ optional($sub->company)->name }} has been verified and activated. Thank you for choosing HR Attendee!</textarea>
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" onclick="navigator.clipboard.writeText(document.getElementById('tgMsg{{ $sub->id }}').value); alert('Telegram message copied!');">
                            <i class="fa-regular fa-copy me-1"></i> Copy Template
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                @if($sub->status === 'Pending Approval')
                <a href="{{ route('superadmin.subscriptions.approve', $sub) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="fa-solid fa-circle-check me-1"></i> Approve & Activate Account
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
