@extends('layouts.app')

@section('title', 'Subscriptions & Billing')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Subscriptions & Billing <span class="badge bg-secondary fs-6">Super Admin</span></h2>
    <a href="{{ route('superadmin.subscriptions.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Subscription</a>
</div>

{{-- Pending Approval Alert --}}
@php $pendingCount = $subscriptions->where('status', 'Pending Approval')->count(); @endphp
@if($pendingCount > 0)
<div class="alert alert-warning border-warning d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-hourglass-half fa-lg"></i>
    <span><strong>{{ $pendingCount }} pending payment(s)</strong> awaiting your review. Please verify the receipt and approve to activate accounts.</span>
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="ps-4">Company</th>
                    <th>Admin / Telegram</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Period</th>
                    <th>Receipt</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr class="{{ $sub->status === 'Pending Approval' ? 'table-warning' : '' }}">
                    <td class="ps-4">
                        <div class="fw-bold">{{ optional($sub->company)->name }}</div>
                        <small class="text-muted">ID: {{ $sub->company_id }}</small>
                    </td>
                    <td>
                        @php $admin = $sub->company?->users?->where('role','Company Admin')->first(); @endphp
                        @if($admin)
                            <div class="small fw-semibold">{{ $admin->name }}</div>
                            @if($admin->telegram_username)
                                <button onclick="sendCredentials('{{ $admin->telegram_username }}', '{{ $admin->email }}', '{{ $admin->name }}')"
                                   class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none" style="cursor: pointer; border: none;">
                                    <i class="fa-brands fa-telegram me-1"></i>{{ $admin->telegram_username }}
                                </button>
                            @else
                                <small class="text-muted">No Telegram</small>
                            @endif
                        @else
                            <small class="text-muted">—</small>
                        @endif
                    </td>
                    <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $sub->plan }}</span></td>
                    <td><span class="fw-bold text-dark">${{ number_format($sub->price, 2) }}</span></td>
                    <td>
                        <small class="d-block">{{ \Carbon\Carbon::parse($sub->start_date)->format('d M y') }}</small>
                        <small class="text-muted">to {{ \Carbon\Carbon::parse($sub->end_date)->format('d M y') }}</small>
                    </td>
                    <td>
                        @if($sub->receipt_path)
                            <a href="{{ Storage::url($sub->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fa-solid fa-file-invoice"></i> View
                            </a>
                        @else
                            <span class="text-muted small">No Receipt</span>
                        @endif
                    </td>
                    <td>
                        @if($sub->status === 'Active' || $sub->status === 'Approved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3">Active</span>
                        @elseif($sub->status === 'Pending Approval')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3">
                                <i class="fa-solid fa-clock me-1"></i>Pending
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3">{{ $sub->status }}</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        {{-- Approve button (only for pending) --}}
                        @if($sub->status === 'Pending Approval')
                            <form action="{{ route('superadmin.subscriptions.approve', $sub) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this payment and activate the account?')">
                                    <i class="fa-solid fa-circle-check"></i> Approve
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('superadmin.subscriptions.edit', $sub) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('superadmin.subscriptions.destroy', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subscription record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No subscription records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
