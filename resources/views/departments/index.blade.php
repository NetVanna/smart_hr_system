@extends('layouts.app')

@section('title', __('messages.departments'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('messages.departments') }}</h2>
    <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> {{ __('messages.add_department') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4">{{ __('messages.department_id') }}</th>
                        <th class="border-0">{{ __('messages.department_name') }}</th>
                        <th class="border-0">{{ __('messages.description') }}</th>
                        <th class="border-0 pe-4 text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td class="ps-4">{{ $department->id }}</td>
                        <td><strong>{{ $department->department_name }}</strong></td>
                        <td class="text-muted">{{ Str::limit($department->description, 50) }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_department_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">{{ __('messages.no_departments_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
