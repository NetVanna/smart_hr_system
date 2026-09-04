@extends('layouts.app')

@section('title', __('messages.invite_team_member') ?? 'Invite Team Member')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('company.users.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1 mb-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>{{ __('messages.back_to_users') ?? 'Back to Team Access' }}</span>
                </a>
                <h3 class="fw-bold mb-0 text-dark">{{ __('messages.invite_team_member') ?? 'Invite Team Member' }}</h3>
                <p class="text-muted small mb-0">{{ __('messages.invite_team_subtitle') ?? 'Grant workspace permissions to administrators, HR personnel, or staff members.' }}</p>
            </div>
        </div>

        <div class="card card-attendee shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('company.users.store') }}" method="POST">
                    @csrf

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">
                            {{ __('messages.assign_role') ?? '1. Select Workspace Role' }} <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="card h-100 p-3 border rounded-3 cursor-pointer role-card" style="cursor: pointer;">
                                    <div class="d-flex align-items-start gap-2">
                                        <input type="radio" name="role" value="HR Manager" class="form-check-input mt-1" checked>
                                        <div>
                                            <span class="fw-bold text-dark d-block">HR Manager</span>
                                            <small class="text-muted" style="font-size: 0.78rem;">Approves leaves, monitors daily attendance, and manages shifts & payroll.</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="card h-100 p-3 border rounded-3 cursor-pointer role-card" style="cursor: pointer;">
                                    <div class="d-flex align-items-start gap-2">
                                        <input type="radio" name="role" value="Company Admin" class="form-check-input mt-1" {{ old('role') == 'Company Admin' ? 'checked' : '' }}>
                                        <div>
                                            <span class="fw-bold text-dark d-block">Company Admin</span>
                                            <small class="text-muted" style="font-size: 0.78rem;">Full company authority: settings, billing, team invites, and organization scale.</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="card h-100 p-3 border rounded-3 cursor-pointer role-card" style="cursor: pointer;">
                                    <div class="d-flex align-items-start gap-2">
                                        <input type="radio" name="role" value="Employee" class="form-check-input mt-1" {{ old('role') == 'Employee' ? 'checked' : '' }}>
                                        <div>
                                            <span class="fw-bold text-dark d-block">Employee</span>
                                            <small class="text-muted" style="font-size: 0.78rem;">Staff portal access: QR / GPS clock-in, leave requests, and digital ID card.</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Basic Profile Information -->
                    <h6 class="fw-bold text-dark mb-3">2. Account Credentials & Contact</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.full_name') ?? 'Full Name' }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Sreysros Chan" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.email') ?? 'Email Address' }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="sreysros@company.kh" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.phone') ?? 'Cambodia Phone Number' }}</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="012 345 678">
                            <small class="text-muted" style="font-size: 0.74rem;">Allows mobile phone login.</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.telegram') ?? 'Telegram Username' }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">@</span>
                                <input type="text" name="telegram_username" class="form-control @error('telegram_username') is-invalid @enderror" value="{{ old('telegram_username') }}" placeholder="username">
                            </div>
                            <small class="text-muted" style="font-size: 0.74rem;">For attendance and leave approval notifications.</small>
                            @error('telegram_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Link to Staff Profile -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-dark">{{ __('messages.link_employee_profile') ?? 'Link to Existing Staff Profile (Optional)' }}</label>
                        <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                            <option value="">— {{ __('messages.no_linked_employee') ?? 'No linked staff profile (Stand-alone Admin/HR)' }} —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}" {{ old('employee_id') == $emp->employee_id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }}) - {{ $emp->position ?? 'Staff' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size: 0.74rem;">If this user is also an employee, link their employee ID to sync clock-in and payslip records.</small>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Password Section -->
                    <h6 class="fw-bold text-dark mb-3">3. Set Initial Password</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.password') ?? 'Password' }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 characters" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.confirm_password') ?? 'Confirm Password' }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('company.users.index') }}" class="btn btn-light rounded-pill px-4 border">{{ __('messages.cancel') ?? 'Cancel' }}</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="fa-solid fa-user-check me-1"></i> {{ __('messages.create_user_btn') ?? 'Create Account' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
