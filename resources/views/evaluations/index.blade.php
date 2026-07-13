@extends('layouts.app')

@section('title', 'Performance Evaluations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fa-solid fa-chart-line me-2"></i> Performance Management</h3>
    <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> New Evaluation
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Period</th>
                        <th>Rating</th>
                        <th>Date</th>
                        <th>Evaluator</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluations as $evaluation)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                            @if($evaluation->employee->profile_photo)
                                <img src="{{ asset('storage/' . $evaluation->employee->profile_photo) }}" alt="Photo" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <span class="text-secondary small fw-bold">{{ substr($evaluation->employee->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                                <div>
                                    <span class="d-block fw-bold">{{ $evaluation->employee->first_name }} {{ $evaluation->employee->last_name }}</span>
                                    <small class="text-muted">{{ $evaluation->employee->position }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark px-2 py-1">{{ $evaluation->period }}</span></td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $evaluation->rating ? 'text-warning' : 'text-muted opacity-25' }} small"></i>
                            @endfor
                            <span class="ms-1 fw-bold">{{ $evaluation->rating }}/5</span>
                        </td>
                        <td><small class="text-muted">{{ \Carbon\Carbon::parse($evaluation->evaluation_date)->format('d M Y') }}</small></td>
                        <td><small class="fw-bold">{{ $evaluation->evaluator->name }}</small></td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('evaluations.show', $evaluation) }}" class="btn btn-sm btn-outline-primary" title="Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
