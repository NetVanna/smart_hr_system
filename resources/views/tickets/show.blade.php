@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h5>
                <span class="badge border {{ $ticket->status === 'Open' ? 'text-success border-success' : 'text-primary border-primary' }}">
                    {{ $ticket->status }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Submitted By</small>
                        <strong>{{ $ticket->user->name }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Category</small>
                        <strong>{{ $ticket->category }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Priority</small>
                        <strong>{{ $ticket->priority }}</strong>
                    </div>
                </div>

                <div class="p-3 bg-light rounded mb-4">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $ticket->message }}</p>
                </div>

                @if(auth()->user()->role === 'Super Admin')
                <hr>
                <div class="mt-4">
                    <h6>Update Status (Super Admin)</h6>
                    <form action="{{ route('superadmin.tickets.updateStatus', $ticket) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select w-auto">
                            <option value="Open" {{ $ticket->status === 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Pending" {{ $ticket->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Resolved" {{ $ticket->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ $ticket->status === 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary btn-sm">Back to Tickets</a>
            </div>
        </div>
    </div>
</div>
@endsection
