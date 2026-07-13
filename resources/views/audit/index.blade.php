@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-shield-halved me-2"></i> System Audit Logs</h3>
    <span class="badge bg-light text-dark border p-2 px-3">Enhanced Security</span>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>IP Address</th>
                        <th>Date & Time</th>
                        <th class="pe-4 text-end">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <strong>{{ $log->user->name ?? 'System' }}</strong>
                            <small class="d-block text-muted">{{ $log->company->name ?? 'N/A' }}</small>
                        </td>
                        <td><span class="badge bg-light text-primary border">{{ $log->action }}</span></td>
                        <td>
                            @if($log->model_type)
                                <small class="text-muted">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="font-monospace">{{ $log->ip_address }}</span></td>
                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        <td class="pe-4 text-end">
                            @if($log->old_values || $log->new_values)
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                    <i class="fa-solid fa-code"></i>
                                </button>

                                <!-- Log Details Modal -->
                                <div class="modal fade text-start" id="logModal{{ $log->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Change Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-danger">Old values</h6>
                                                        <pre class="bg-light p-2 rounded small"><code>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</code></pre>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-success">New values</h6>
                                                        <pre class="bg-light p-2 rounded small"><code>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    <div class="card-footer bg-white border-0 py-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
