@extends('layouts.app')

@section('title', __('messages.leave_management'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('messages.leave_management') }}</h2>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> {{ __('messages.apply_leave') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 ps-4">{{ __('messages.employees') }}</th>
                    <th class="border-0">{{ __('messages.leave_type') }}</th>
                    <th class="border-0">{{ __('messages.duration') }}</th>
                    <th class="border-0">{{ __('messages.status') }}</th>
                    <th class="border-0 pe-4 text-end">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            @if($leave->employee && $leave->employee->profile_photo)
                                <img src="{{ asset('storage/' . $leave->employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                            @elseif($leave->employee)
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <span class="text-secondary small fw-bold">{{ substr($leave->employee->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <strong>{{ optional($leave->employee)->first_name }} {{ optional($leave->employee)->last_name }}</strong><br>
                                <small class="text-muted">{{ optional($leave->employee)->employee_id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $leave->leave_type }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                    </td>
                    <td>
                        @if($leave->status === 'Approved')
                            <span class="badge bg-success">{{ __('messages.approved') }}</span>
                        @elseif($leave->status === 'Rejected')
                            <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ __('messages.pending') }}</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        @if(in_array(auth()->user()->role, ['Super Admin', 'Company Admin', 'HR Manager']))
                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i> {{ __('messages.review') }}</a>
                        @endif
                        <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_leave_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">{{ __('messages.no_leaves_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
