@extends('layouts.app')

@section('title', __('messages.manage_companies') ?? 'Manage Tenants')

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                    <i class="fa-solid fa-shield-halved me-1"></i> Super Admin
                </span>
                <span class="text-muted small">• Multi-Tenant Directory</span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ __('messages.manage_companies') ?? 'Manage Companies' }}</h3>
            <p class="text-muted small mb-0">Oversee all registered organization workspaces, branch setups, and staff scale.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('messages.add_company') ?? 'Add New Company' }}</span>
            </a>
        </div>
    </div>

    @if(session('admin_password'))
    <div class="alert alert-info border-0 shadow-sm rounded-4 p-3 d-flex justify-content-between align-items-center" style="background-color: #e0f2fe; color: #0369a1;">
        <div>
            <i class="fa-solid fa-key me-2"></i> <strong>New Admin Credentials Generated:</strong> 
            Email: <code>{{ session('admin_email') }}</code> | Password: <code>{{ session('admin_password') }}</code>
            <br><small class="opacity-75">Send these credentials to the client via Telegram or SMS.</small>
        </div>
        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="navigator.clipboard.writeText('{{ session('admin_password') }}'); alert('Password copied!')">
            <i class="fa-regular fa-copy me-1"></i> Copy Password
        </button>
    </div>
    @endif

    <!-- 4 Mini Metric Overview -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3">
        <div class="col">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold text-uppercase">Total Tenants</span>
                    <i class="fa-solid fa-building text-primary"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $companies->total() }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold text-uppercase">Active Paid</span>
                    <i class="fa-solid fa-circle-check text-success"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $companies->where('subscription_status', 'Active')->count() }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold text-uppercase">In Free Trial</span>
                    <i class="fa-solid fa-hourglass-half text-warning"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $companies->where('subscription_status', 'Trial')->count() }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card card-attendee p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold text-uppercase">Total Branches</span>
                    <i class="fa-solid fa-map-location-dot text-info"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $companies->sum('branches_count') }}</h4>
            </div>
        </div>
    </div>

    <!-- Companies List Card -->
    <div class="card card-attendee">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-attendee align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Company & Location</th>
                            <th>Branches & Scale</th>
                            <th>Admin & Telegram</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <!-- Company & Location -->
                            <td class="ps-4">
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $company->name }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 260px;">
                                    <i class="fa-regular fa-envelope me-1"></i> {{ $company->email }}
                                </div>
                                @if($company->address)
                                <small class="text-muted text-truncate d-block" style="max-width: 260px; font-size: 0.74rem;">
                                    <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $company->address }}
                                </small>
                                @endif
                            </td>

                            <!-- Branches & Scale -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1 text-start" style="font-size: 0.75rem; width: fit-content;">
                                        <i class="fa-solid fa-map-location-dot me-1 text-primary"></i> <strong>{{ $company->branches_count }}</strong> Branches
                                    </span>
                                    <span class="badge bg-light text-dark border rounded-pill px-2 py-1 text-start" style="font-size: 0.75rem; width: fit-content;">
                                        <i class="fa-solid fa-users me-1 text-success"></i> <strong>{{ $company->employees_count }}</strong> Staff
                                    </span>
                                </div>
                            </td>

                            <!-- Admin & Telegram -->
                            <td>
                                @php $admin = $company->users->first(); @endphp
                                @if($admin)
                                    <div class="fw-semibold text-dark small">{{ $admin->name }}</div>
                                    <small class="text-muted d-block">{{ $admin->email }}</small>
                                    @if($admin->telegram_username)
                                        <a href="https://t.me/{{ $admin->telegram_username }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none mt-1" style="font-size: 0.72rem;">
                                            <i class="fa-brands fa-telegram me-1"></i> @ {{ $admin->telegram_username }}
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted small">No Admin</span>
                                @endif
                            </td>

                            <!-- Plan -->
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                    {{ $company->subscription_plan }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                @if($company->subscription_status === 'Active')
                                    <span class="badge badge-attendee-active px-3 py-1">{{ __('messages.active') }}</span>
                                @elseif($company->subscription_status === 'Trial')
                                    <span class="badge badge-attendee-pending px-3 py-1">{{ __('messages.trial') }}</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">{{ $company->subscription_status }}</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="pe-4 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <!-- 1-Click Support Login (Impersonate) -->
                                    <a href="{{ route('superadmin.companies.impersonate', $company) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-flex align-items-center gap-1 shadow-sm" title="Support Login as Company Admin">
                                        <i class="fa-solid fa-user-shield"></i>
                                        <span style="font-size: 0.78rem;">Support Login</span>
                                    </a>

                                    <!-- Extend Trial Quick Action (if Trial or Expired) -->
                                    @if($company->subscription_status === 'Trial' || $company->subscription_status === 'Expired')
                                    <form action="{{ route('superadmin.companies.extend-trial', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Extend 14-day free trial for {{ addslashes($company->name) }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2 py-1" title="Extend Trial +14 Days">
                                            <i class="fa-solid fa-clock-rotate-left"></i> +14d
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Edit -->
                                    <a href="{{ route('superadmin.companies.edit', $company) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Edit">
                                        <i class="fa-solid fa-pen" style="font-size: 0.8rem;"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('WARNING: Deleting {{ addslashes($company->name) }} will permanently remove all company data, branches, and employees. Proceed?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Delete">
                                            <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-building-circle-exclamation fa-2x mb-2 text-muted opacity-50"></i>
                                <div>No companies registered yet.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($companies->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $companies->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
