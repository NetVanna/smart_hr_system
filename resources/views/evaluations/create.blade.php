@extends('layouts.app')

@section('title', 'New Performance Evaluation')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Record Performance Review</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluations.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee to Evaluate</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->position }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Review Period</label>
                            <select name="period" class="form-select" required>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Annual">Annual</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Review Date</label>
                            <input type="date" name="evaluation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <label class="form-label d-block mb-3 fw-bold">Performance Rating (1-5 Stars)</label>
                        <div class="rating-selector fs-2">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required {{ $i == 3 ? 'checked' : '' }}>
                                <label for="star{{ $i }}"><i class="fa-solid fa-star"></i></label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Performance Comments</label>
                        <textarea name="comments" class="form-control" rows="4" placeholder="Detail the employee's strengths and areas for improvement..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Future Goals / Action Plan</label>
                        <textarea name="future_goals" class="form-control" rows="4" placeholder="Set measurable goals for the next period..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('evaluations.index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary px-5">Submit Evaluation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-selector { display: flex; flex-direction: row-reverse; justify-content: center; }
    .rating-selector input { display: none; }
    .rating-selector label { color: #dee2e6; cursor: pointer; transition: color 0.2s; padding: 0 5px; }
    .rating-selector input:checked ~ label,
    .rating-selector label:hover,
    .rating-selector label:hover ~ label { color: #ffc107; }
</style>
@endsection
