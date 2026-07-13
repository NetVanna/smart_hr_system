@extends('layouts.app')

@section('title', 'Expense Claims')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-file-invoice-dollar me-2"></i> Expense & Reimbursements</h3>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> New Claim
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($expense->employee->profile_photo)
                                    <img src="{{ asset('storage/' . $expense->employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <span class="text-secondary small fw-bold">{{ substr($expense->employee->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="d-block fw-bold">{{ $expense->employee->first_name }} {{ $expense->employee->last_name }}</span>
                                    <small class="text-muted">{{ $expense->employee->position }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark px-2 py-1">{{ $expense->category }}</span></td>
                        <td>
                            <strong class="{{ $expense->status === 'Approved' ? 'text-success' : ($expense->status === 'Rejected' ? 'text-danger' : '') }}">
                                {{ $expense->currency }} {{ number_format($expense->amount, 2) }}
                            </strong>
                        </td>
                        <td>
                            @if($expense->status === 'Pending')
                                <span class="badge bg-warning px-2 py-1">Pending</span>
                            @elseif($expense->status === 'Approved')
                                <span class="badge bg-success px-2 py-1">Approved</span>
                            @else
                                <span class="badge bg-danger px-2 py-1" title="{{ $expense->rejection_reason }}">Rejected</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $expense->created_at->format('d M Y') }}</small></td>
                        <td class="pe-4 text-end">
                            @if($expense->receipt_photo)
                                <a href="{{ asset('storage/' . $expense->receipt_photo) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Receipt">
                                    <i class="fa-solid fa-receipt"></i>
                                </a>
                            @endif
                            
                            @if($expense->status === 'Pending')
                                <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="fa-solid fa-check"></i></button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $expense->id }}" title="Reject">
                                    <i class="fa-solid fa-times"></i>
                                </button>

                                <!-- Reject Modal -->
                                <div class="modal fade text-start" id="rejectModal{{ $expense->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('expenses.reject', $expense) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Expense Claim</h5>
                                                    <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $expense->id }}"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label">Reason for Rejection</label>
                                                    <textarea name="reason" class="form-control" required rows="3"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $expense->id }}">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Claim</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
