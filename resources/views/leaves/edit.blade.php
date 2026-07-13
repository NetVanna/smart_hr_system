@extends('layouts.app')

@section('title', __('messages.review_leave'))

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('leaves.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> {{ __('messages.leaves') }}</a> / {{ __('messages.review') }}</h2>
</div>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <h5 class="card-title">{{ __('messages.leave_request_details') }}</h5>
        <hr>
        <p><strong>{{ __('messages.employees') }}:</strong> {{ optional($leave->employee)->first_name }} {{ optional($leave->employee)->last_name }}</p>
        <p><strong>{{ __('messages.leave_type') }}:</strong> {{ $leave->leave_type }}</p>
        <p><strong>{{ __('messages.duration') }}:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} {{ __('messages.to') ?? 'to' }} {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
        <p><strong>{{ __('messages.reason') }}:</strong></p>
        <blockquote class="bg-light p-3 rounded border">
            {{ $leave->reason }}
        </blockquote>

        <form action="{{ route('leaves.update', $leave) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">{{ __('messages.update_status') }}</label>
                <select name="status" class="form-select w-50" required>
                    <option value="Pending" {{ $leave->status == 'Pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="Approved" {{ $leave->status == 'Approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                    <option value="Rejected" {{ $leave->status == 'Rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> {{ __('messages.save_status') }}</button>
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
