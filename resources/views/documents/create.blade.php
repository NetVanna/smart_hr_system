@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="mb-4">
    <h2><a href="{{ route('documents.index') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left"></i> Documents</a> / Upload</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Target Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->employee_id }} - {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Document Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Q4 Performance Review 2025" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description (Optional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief details about the document...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Select File <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="file" name="document_file" class="form-control @error('document_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png" required>
                    <span class="input-group-text"><i class="fa-solid fa-file-arrow-up"></i></span>
                </div>
                <div class="form-text small">Max size: 5MB (PDF, DOC, JPG, PNG)</div>
                @error('document_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload Document</button>
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
