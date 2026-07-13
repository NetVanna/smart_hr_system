@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-ticket me-2"></i> Support Tickets</h3>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> New Ticket
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $ticket->subject }}</div>
                        </td>
                        <td>{{ $ticket->category }}</td>
                        <td>
                            @php
                                $priorityClass = match($ticket->priority) {
                                    'Urgent' => 'bg-danger',
                                    'High' => 'bg-warning text-dark',
                                    'Medium' => 'bg-info text-dark',
                                    'Low' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $priorityClass }}">{{ $ticket->priority }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($ticket->status) {
                                    'Open' => 'text-success border-success',
                                    'Pending' => 'text-warning border-warning',
                                    'Resolved' => 'text-primary border-primary',
                                    'Closed' => 'text-muted border-secondary',
                                    default => 'text-muted border-secondary'
                                };
                            @endphp
                            <span class="badge border {{ $statusClass }}">{{ $ticket->status }}</span>
                        </td>
                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No tickets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tickets->hasPages())
    <div class="card-footer bg-white">
        {{ $tickets->links() }}
    </div>
    @endif
</div>
@endsection
