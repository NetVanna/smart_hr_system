@extends('layouts.app')

@section('title', 'Evaluation Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-4 px-4 bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $evaluation->employee->first_name }} {{ $evaluation->employee->last_name }}</h4>
                        <span class="badge bg-primary px-3">{{ $evaluation->period }} Review</span>
                    </div>
                    <div class="text-end">
                        <div class="fs-3 fw-bold text-warning mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $evaluation->rating ? '' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                        <small class="text-muted d-block">Overall Rating: {{ $evaluation->rating }}/5</small>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row mb-4 bg-light p-3 rounded mx-0">
                    <div class="col-6">
                        <small class="text-muted d-block">Evaluated By</small>
                        <strong>{{ $evaluation->evaluator->name }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted d-block">Evaluation Date</small>
                        <strong>{{ \Carbon\Carbon::parse($evaluation->evaluation_date)->format('d M Y') }}</strong>
                    </div>
                </div>

                <div class="mb-5">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-comment-dots me-2"></i> Evaluator Comments</h6>
                    <div class="p-3 border rounded bg-white shadow-sm">
                        {!! nl2br(e($evaluation->comments)) ?: '<em class="text-muted">No comments provided.</em>' !!}
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-bullseye me-2"></i> Future Goals & Action Plan</h6>
                    <div class="p-3 border rounded bg-white shadow-sm">
                        {!! nl2br(e($evaluation->future_goals)) ?: '<em class="text-muted">No specific goals set.</em>' !!}
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white border-0 py-3 text-center">
                <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Evaluations
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
