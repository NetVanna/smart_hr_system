@extends('layouts.app')

@section('title', 'All Support Tickets')

@section('content')
<div class="mb-4">
    <h3><i class="fa-solid fa-headset me-2"></i> All Support Tickets (Super Admin)</h3>
    <p class="text-muted">Manage technical, billing, and feature requests from all companies.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Company</th>
                        <th>User</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="ps-4">
                            <strong>{{ $ticket->company->name ?? 'N/A' }}</strong>
                        </td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 30) }}</td>
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
                        <td class="text-end pe-4">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Process</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No support tickets found.</td>
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
