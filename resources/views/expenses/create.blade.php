@extends('layouts.app')

@section('title', 'Submit Expense')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-file-circle-plus me-2 text-primary"></i> New Expense Claim</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->position }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Travel">Travel</option>
                                <option value="Food & Dining">Food & Dining</option>
                                <option value="Office Supplies">Office Supplies</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Training/Cert">Training/Cert</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount</label>
                            <div class="input-group">
                                <select name="currency" class="input-group-text border-end-0 bg-light" style="width: 80px;">
                                    <option value="USD">USD</option>
                                    <option value="KHR">KHR</option>
                                </select>
                                <input type="number" name="amount" class="form-control" step="0.01" required placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Purpose</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Explain the expense..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Receipt Photo / Document</label>
                        <div class="receipt-upload border rounded p-3 text-center bg-light">
                            <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary mb-2"></i>
                            <input type="file" name="receipt" class="form-control" required>
                            <small class="text-muted d-block mt-2">Accepted: JPG, PNG, PDF (Max 5MB)</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('expenses.index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary px-5">Submit Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
