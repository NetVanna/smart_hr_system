@extends('layouts.app')

@section('title', __('messages.team_access') ?? 'Team & Role Access')

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                    <i class="fa-solid fa-shield-halved me-1"></i> {{ __('messages.tenant_admin') ?? 'Organization Owner' }}
                </span>
                <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                    {{ auth()->user()->company->name ?? 'Company Workspace' }}
                </span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ __('messages.team_access') ?? 'Team & Role Access' }}</h3>
            <p class="text-muted small mb-0">{{ __('messages.team_access_subtitle') ?? 'Manage administrative access, invite HR Managers and Company Admins, and configure employee login privileges.' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('company.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-user-plus"></i>
                <span>{{ __('messages.invite_member') ?? 'Invite Team Member' }}</span>
            </a>
        </div>
    </div>

    <!-- Quick Role Stat Cards -->
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.all_users') ?? 'All Accounts' }}</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.company_admins') ?? 'Company Admins' }}</div>
                        <div class="fs-4 fw-bold text-primary mt-1">{{ $stats['admins'] }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.hr_managers') ?? 'HR Managers' }}</div>
                        <div class="fs-4 fw-bold text-success mt-1">{{ $stats['hrManagers'] }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.staff_logins') ?? 'Employee Logins' }}</div>
                        <div class="fs-4 fw-bold text-info mt-1">{{ $stats['employees'] }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card card-attendee">
        <div class="card-body p-3">
            <form action="{{ route('company.users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="{{ __('messages.search_name_email_phone') ?? 'Search by name, email, phone, or Telegram...' }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('messages.all_roles') ?? 'All Roles' }}</option>
                        <option value="Company Admin" {{ request('role') == 'Company Admin' ? 'selected' : '' }}>Company Admin</option>
                        <option value="HR Manager" {{ request('role') == 'HR Manager' ? 'selected' : '' }}>HR Manager</option>
                        <option value="Employee" {{ request('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill flex-fill">{{ __('messages.filter') ?? 'Filter' }}</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('company.users.index') }}" class="btn btn-sm btn-light border rounded-pill">{{ __('messages.clear') ?? 'Clear' }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card card-attendee">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-attendee align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">{{ __('messages.member_name') ?? 'Member' }}</th>
                            <th>{{ __('messages.role') ?? 'Role' }}</th>
                            <th>{{ __('messages.contact') ?? 'Contact' }}</th>
                            <th>{{ __('messages.telegram') ?? 'Telegram' }}</th>
                            <th>{{ __('messages.linked_employee') ?? 'Linked Staff Profile' }}</th>
                            <th>{{ __('messages.created_at') ?? 'Joined Date' }}</th>
                            <th class="pe-4 text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <!-- Member Name & Avatar -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #4f46e5, #06b6d4); font-size: 0.85rem; flex-shrink: 0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="badge bg-light text-primary border rounded-pill" style="font-size: 0.68rem;">You</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td>
                                @if($user->role === 'Company Admin')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                                        <i class="fa-solid fa-crown me-1"></i> Company Admin
                                    </span>
                                @elseif($user->role === 'HR Manager')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold">
                                        <i class="fa-solid fa-user-tie me-1"></i> HR Manager
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">
                                        <i class="fa-solid fa-id-badge me-1"></i> Employee
                                    </span>
                                @endif
                            </td>

                            <!-- Contact -->
                            <td>
                                @if($user->phone)
                                    <div class="small text-dark fw-semibold"><i class="fa-solid fa-phone me-1 text-muted"></i> {{ $user->phone }}</div>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>

                            <!-- Telegram -->
                            <td>
                                @if($user->telegram_username)
                                    <a href="https://t.me/{{ $user->telegram_username }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none rounded-pill px-2 py-1">
                                        <i class="fa-brands fa-telegram me-1"></i> @ {{ $user->telegram_username }}
                                    </a>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>

                            <!-- Linked Employee -->
                            <td>
                                @if($user->employee_id)
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                        <i class="fa-solid fa-id-card me-1 text-primary"></i> {{ $user->employee_id }}
                                    </span>
                                @else
                                    <small class="text-muted">Unlinked (Admin only)</small>
                                @endif
                            </td>

                            <!-- Date Joined -->
                            <td>
                                <div class="small text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="pe-4 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <a href="{{ route('company.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Edit Access">
                                        <i class="fa-solid fa-pen" style="font-size: 0.8rem;"></i>
                                    </a>

                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('company.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Revoke account access for {{ addslashes($user->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Remove Access">
                                            <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users fa-3x text-muted mb-3 d-block opacity-50"></i>
                                {{ __('messages.no_users_found') ?? 'No team accounts found.' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
