@extends('layouts.app')

@section('title', __('messages.employees'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('messages.employees') }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.export') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-file-export me-1"></i> {{ __('messages.export_csv') }}
        </a>
        <button type="button" class="btn btn-outline-info" onclick="document.getElementById('importFile').click()">
            <i class="fa-solid fa-file-import me-1"></i> {{ __('messages.import_csv') }}
        </button>
        <form id="importForm" action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="d-none">
            @csrf
            <input type="file" name="csv_file" id="importFile" onchange="document.getElementById('importForm').submit()">
        </form>
        <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> {{ __('messages.add_employee') }}</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 ps-4">{{ __('messages.photo') }}</th>
                    <th class="border-0">{{ __('messages.emp_id') }}</th>
                    <th class="border-0">{{ __('messages.name_position') }}</th>
                    <th class="border-0">{{ __('messages.departments') }}</th>
                    <th class="border-0">{{ __('messages.status') }}</th>
                    <th class="border-0 pe-4 text-end">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td class="ps-4">
                        @if($employee->profile_photo)
                            <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Photo" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ substr($employee->first_name, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $employee->employee_id }}</strong></td>
                    <td>
                        {{ $employee->first_name }} {{ $employee->last_name }}<br>
                        <small class="text-muted">{{ $employee->position ?? __('messages.no_position') }}</small>
                    </td>
                    <td>{{ optional($employee->department)->department_name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $employee->status === 'Active' ? 'bg-success' : ($employee->status === 'On Leave' ? 'bg-info' : 'bg-danger') }}">
                            {{ __('messages.' . strtolower(str_replace(' ', '_', $employee->status))) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('employees.id_card', $employee) }}" class="btn btn-sm btn-outline-info" title="{{ __('messages.id_card') }}"><i class="fa-solid fa-id-card"></i></a>
                        <a href="{{ route('employees.qr', $employee) }}" class="btn btn-sm btn-outline-dark" title="{{ __('messages.view_qr') }}"><i class="fa-solid fa-qrcode"></i></a>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">{{ __('messages.no_employees_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
