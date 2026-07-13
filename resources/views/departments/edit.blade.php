@extends('layouts.app')

@section('title', __('messages.edit_department'))

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('departments.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> {{ __('messages.departments') }}</a> / {{ __('messages.edit') }}</h2>
</div>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('departments.update', $department) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">{{ __('messages.department_name') }} <span class="text-danger">*</span></label>
                <input type="text" name="department_name" class="form-control @error('department_name') is-invalid @enderror" value="{{ old('department_name', $department->department_name) }}" required>
                @error('department_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.description') }}</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $department->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> {{ __('messages.update_department') }}</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
