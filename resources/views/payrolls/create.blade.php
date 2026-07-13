@extends('layouts.app')

@section('title', __('messages.generate_payroll'))

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('payrolls.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> {{ __('messages.payrolls') ?? 'Payrolls' }}</a> / {{ __('messages.add') }}</h2>
</div>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('payrolls.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">{{ __('messages.employees') }} <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                    <option value="">{{ __('messages.select_employee') }}</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->employee_id }} - {{ $emp->first_name }} {{ $emp->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('messages.month') }} <span class="text-danger">*</span></label>
                    <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                            <option value="{{ $m }}" {{ old('month', date('F')) == $m ? 'selected' : '' }}>{{ __('messages.' . strtolower($m)) }}</option>
                        @endforeach
                    </select>
                    @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('messages.year') }} <span class="text-danger">*</span></label>
                    <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', date('Y')) }}" required>
                    @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('messages.basic_salary') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', 0) }}" required>
                    </div>
                    @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('messages.allowance') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="allowance" class="form-control @error('allowance') is-invalid @enderror" value="{{ old('allowance', 0) }}" required>
                    </div>
                    @error('allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('messages.deductions') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="deductions" class="form-control @error('deductions') is-invalid @enderror" value="{{ old('deductions', 0) }}" required>
                    </div>
                    @error('deductions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="alert alert-info py-2">
                {{ __('messages.payroll_formula') }}
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> {{ __('messages.generate_payroll') }}</button>
            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
