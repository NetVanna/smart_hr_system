@extends('layouts.app')

@section('title', __('messages.apply_leave'))

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('leaves.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> {{ __('messages.leaves') }}</a> / {{ __('messages.add') }}</h2>
</div>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('leaves.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('messages.leave_type') }} <span class="text-danger">*</span></label>
                <select name="leave_type" class="form-select @error('leave_type') is-invalid @enderror" required>
                    <option value="">{{ __('messages.select_option') }}</option>
                    <option value="Annual Leave" {{ old('leave_type') == 'Annual Leave' ? 'selected' : '' }}>{{ __('messages.annual_leave') }}</option>
                    <option value="Sick Leave" {{ old('leave_type') == 'Sick Leave' ? 'selected' : '' }}>{{ __('messages.sick_leave') }}</option>
                    <option value="Unpaid Leave" {{ old('leave_type') == 'Unpaid Leave' ? 'selected' : '' }}>{{ __('messages.unpaid_leave') }}</option>
                </select>
                @error('leave_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.start_date') }} <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.end_date') }} <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.reason') }} <span class="text-danger">*</span></label>
                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason') }}</textarea>
                @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> {{ __('messages.submit_request') }}</button>
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
