@extends('layouts.app')

@section('title', 'Manage Companies')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Companies <span class="badge bg-secondary fs-6">Super Admin</span></h2>
    <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Company</a>
</div>

@if(session('admin_password'))
<div class="alert alert-info border-0 shadow-sm mb-4 d-flex justify-content-between align-items-center">
    <div>
        <i class="fa-solid fa-key me-2"></i> <strong>New Admin Credentials:</strong> 
        Email: <code>{{ session('admin_email') }}</code> | Password: <code>{{ session('admin_password') }}</code>
        <br><small class="text-muted">Please copy these and send them to the admin via Telegram. They will disappear after you refresh the page.</small>
    </div>
    <button class="btn btn-sm btn-outline-info" onclick="navigator.clipboard.writeText('{{ session('admin_password') }}'); alert('Password copied to clipboard!')">Copy Password</button>
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="ps-4">Company Name</th>
                    <th>Contact</th>
                    <th>Admin Account</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                <tr>
                    <td class="ps-4">
                        <strong>{{ $company->name }}</strong><br>
                        <small class="text-muted">Joined: {{ \Carbon\Carbon::parse($company->created_at)->format('M d, Y') }}</small>
                    </td>
                    <td>
                        <i class="fa-regular fa-envelope me-1"></i> {{ $company->email }}<br>
                        <i class="fa-solid fa-phone me-1"></i> {{ $company->phone ?? 'N/A' }}
                    </td>
                    <td>
                        @php $admin = $company->users()->where('role', 'Company Admin')->first(); @endphp
                        @if($admin)
                            <strong>{{ $admin->name }}</strong><br>
                            <small class="text-muted">{{ $admin->email }}</small>
                            @if($admin->telegram_username)
                                <div class="mt-1">
                                    <button onclick="sendCredentials('{{ $admin->telegram_username }}', '{{ $admin->email }}', '{{ $admin->name }}')" class="btn btn-sm btn-info text-white py-0 px-2" style="font-size: 0.75rem;">
                                        <i class="fa-brands fa-telegram"></i> @ {{ $admin->telegram_username }}
                                    </button>
                                </div>
                            @endif
                        @else
                            <span class="text-muted">No Admin</span>
                        @endif
                    </td>
                    <td><span class="badge bg-info text-dark">{{ $company->subscription_plan }}</span></td>
                    <td>
                        @if($company->subscription_status === 'Active')
                            <span class="badge bg-success">Active</span>
                        @elseif($company->subscription_status === 'Trial')
                            <span class="badge bg-warning text-dark">Trial</span>
                        @else
                            <span class="badge bg-danger">{{ $company->subscription_status }}</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('superadmin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('WARNING: Deleting a company deletes ALL their data. Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No companies registered yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
