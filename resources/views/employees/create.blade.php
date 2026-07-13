@extends('layouts.app')

@section('title', __('messages.add_employee'))

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('employees.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> {{ __('messages.employees') }}</a> / {{ __('messages.add') }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h5 class="mb-3 border-bottom pb-2">{{ __('messages.basic_info') }}</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.emp_id') }} <span class="text-danger">*</span></label>
                    <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id') }}" required>
                    @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.departments') }}</label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">{{ __('messages.select_dept') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.first_name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.last_name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.position') }} <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" placeholder="{{ __('messages.position_placeholder') }}" required>
                    @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.joining_date') }} <span class="text-danger">*</span></label>
                    <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', date('Y-m-d')) }}" required>
                    @error('joining_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('messages.salary') }}</label>
                    <input type="number" step="0.01" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}">
                    @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">{{ __('messages.profile_photo') }}</label>
                <div class="d-flex align-items-start gap-3">
                    <div class="shrink-0">
                        <img id="photoPreview" src="#" alt="Preview" class="rounded border" style="width: 120px; height: 120px; object-fit: cover; display: none;">
                    </div>
                    <div class="grow">
                        <input type="file" name="profile_photo" id="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*" onchange="previewPhoto(event)">
                        <div class="form-text">{{ __('messages.photo_help') }}</div>
                        @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> {{ __('messages.save_employee') }}</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewPhoto(event) {
        const preview = document.getElementById('photoPreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }
</script>
@endpush
