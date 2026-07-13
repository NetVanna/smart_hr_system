@extends('layouts.app')

@section('title', __('messages.id_card'))

@section('content')
<div class="no-print">
    <div class="row justify-content-center py-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <a href="{{ route('employees.index') }}" class="text-decoration-none text-muted">
                        <i class="fa-solid fa-arrow-left"></i> {{ __('messages.employees') }}
                    </a>
                    / {{ __('messages.id_card') }}
                </h4>
                <div>
                    <button onclick="window.print()" class="btn btn-dark px-4">
                        <i class="fa-solid fa-print me-1"></i> {{ __('messages.print_id') }}
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4 ms-2">
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7 col-sm-9 col-12">
        <div class="id-card shadow-lg" id="printableCard">
            <div class="id-card-header">
                <div class="id-card-header-bg"></div>
                <div class="id-card-header-content">
                    <div class="company-logo-circle">
                        {{ strtoupper(mb_substr($company->name ?? 'HR', 0, 2)) }}
                    </div>
                    <h4 class="company-name">{{ strtoupper($company->name) }}</h4>
                    <span class="id-badge">{{ __('messages.official_employee_id') }}</span>
                </div>
            </div>

            <div class="id-card-body">
                <div class="photo-section">
                    <div class="id-photo-wrapper">
                        @if($employee->profile_photo)
                            <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Photo" class="id-photo">
                        @else
                            <div class="id-photo id-photo-placeholder">
                                <span>{{ strtoupper(mb_substr($employee->first_name, 0, 1)) }}{{ strtoupper(mb_substr($employee->last_name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="employee-name">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    <p class="employee-position">{{ $employee->position ?? '—' }}</p>
                </div>

                <div class="id-divider">
                    <span></span>
                    <i class="fa-solid fa-star"></i>
                    <span></span>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <small class="detail-label">{{ __('messages.employee_id_label') }}</small>
                        <strong class="detail-value">#{{ $employee->employee_id }}</strong>
                    </div>
                    <div class="detail-item">
                        <small class="detail-label">{{ __('messages.departments') }}</small>
                        <strong class="detail-value">{{ $employee->department->department_name ?? 'N/A' }}</strong>
                    </div>
                    <div class="detail-item">
                        <small class="detail-label">{{ __('messages.join_date') }}</small>
                        <strong class="detail-value">{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : '—' }}</strong>
                    </div>
                    <div class="detail-item">
                        <small class="detail-label">{{ __('messages.status') }}</small>
                        @php
                            $statusStr = mb_strtolower($employee->status ?? 'active');
                            $statusClass = match(true) {
                                $statusStr === 'active' => 'status-active',
                                $statusStr === 'inactive' => 'status-inactive',
                                str_contains($statusStr, 'leave') => 'status-on-leave',
                                $statusStr === 'terminated' => 'status-terminated',
                                default => 'status-active',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ $employee->status ?? 'Active' }}
                        </span>
                    </div>
                </div>

                <div class="id-divider">
                    <span></span>
                    <i class="fa-solid fa-qrcode"></i>
                    <span></span>
                </div>

                <div class="qr-section">
                    <div class="qr-code-box">
                        @if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode'))
                            {!! QrCode::size(110)->margin(1)->generate($employee->employee_id) !!}
                        @else
                            <div class="qr-fallback">
                                <i class="fa-solid fa-qrcode fa-5x text-muted"></i>
                                <small class="d-block mt-2">{{ $employee->employee_id }}</small>
                            </div>
                        @endif
                    </div>
                    <small class="qr-hint">{{ __('messages.scan_for_verification') }}</small>
                </div>
            </div>

            <div class="id-card-footer">
                {{ __('messages.secured_platform') }}
            </div>
        </div>
    </div>
</div>

<style>
    .id-card {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    .id-card-header {
        position: relative;
        padding: 28px 20px 22px;
        text-align: center;
        color: #fff;
        overflow: hidden;
    }
    .id-card-header-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #1a237e 0%, #4a148c 50%, #6a1b9a 100%);
    }
    .id-card-header-content {
        position: relative;
        z-index: 1;
    }
    .company-logo-circle {
        width: 52px;
        height: 52px;
        margin: 0 auto 10px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 1px;
    }
    .company-name {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 1.5px;
        margin-bottom: 6px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .id-badge {
        display: inline-block;
        font-size: 10px;
        letter-spacing: 3px;
        text-transform: uppercase;
        background: rgba(255,255,255,0.15);
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 600;
    }
    .id-card-body {
        padding: 24px 24px 16px;
    }
    .photo-section {
        text-align: center;
        margin-bottom: 8px;
    }
    .id-photo-wrapper {
        width: 110px;
        height: 110px;
        margin: 0 auto 14px;
    }
    .id-photo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e8eaf6;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .id-photo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        font-size: 36px;
        font-weight: 700;
    }
    .employee-name {
        font-size: 20px;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 2px;
    }
    .employee-position {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0;
    }
    .id-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 16px 0;
    }
    .id-divider span {
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }
    .id-divider i {
        color: #9e9e9e;
        font-size: 10px;
    }
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 4px;
    }
    .detail-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px 12px;
    }
    .detail-label {
        display: block;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #9e9e9e;
        margin-bottom: 2px;
    }
    .detail-value {
        font-size: 13px;
        color: #212529;
        font-weight: 600;
    }
    .status-badge {
        display: inline-block;
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 600;
    }
    .status-active { background: #e8f5e9; color: #2e7d32; }
    .status-inactive { background: #fafafa; color: #757575; }
    .status-on-leave { background: #fff3e0; color: #e65100; }
    .status-terminated { background: #ffebee; color: #c62828; }

    .qr-section {
        text-align: center;
    }
    .qr-code-box {
        display: inline-block;
        padding: 8px;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 6px;
    }
    .qr-code-box svg {
        display: block;
    }
    .qr-fallback {
        width: 110px;
        height: 110px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .qr-hint {
        font-size: 10px;
        color: #9e9e9e;
        letter-spacing: 0.5px;
    }
    .id-card-footer {
        background: #1a237e;
        color: rgba(255,255,255,0.7);
        text-align: center;
        padding: 10px;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* ========================================
       PRINT: Hide layout, show card on A4
       ======================================== */
    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        body { background: #fff !important; }

        /* Hide app chrome */
        nav, .navbar, .sidebar, .no-print,
        header, .card-header, .breadcrumb {
            display: none !important;
        }

        /* Card prints at real credit-card size */
        #printableCard {
            width: 86mm;
            box-shadow: none !important;
            border: 1px solid #ccc;
            margin: 10mm auto;
            position: static;
            transform: none;
            overflow: hidden;
        }

        /* Scale down inner elements */
        #printableCard .id-card-header { padding: 4mm 5mm 3mm; }
        #printableCard .company-logo-circle { width: 9mm; height: 9mm; font-size: 9pt; }
        #printableCard .company-name { font-size: 9pt; letter-spacing: 0.5pt; margin-bottom: 2px; }
        #printableCard .id-badge { font-size: 6pt; letter-spacing: 1pt; padding: 1px 8px; }
        #printableCard .id-card-body { padding: 3mm 6mm 2mm; }
        #printableCard .id-photo-wrapper { width: 20mm; height: 20mm; margin-bottom: 2mm; }
        #printableCard .id-photo { border-width: 2pt; }
        #printableCard .employee-name { font-size: 10pt; }
        #printableCard .employee-position { font-size: 7pt; }
        #printableCard .id-divider { gap: 4mm; margin: 3mm 0; }
        #printableCard .id-divider i { font-size: 6pt; }
        #printableCard .details-grid { gap: 2mm; }
        #printableCard .detail-item { padding: 2mm 3mm; border-radius: 2mm; }
        #printableCard .detail-label { font-size: 5pt; letter-spacing: 0.3pt; }
        #printableCard .detail-value { font-size: 7pt; }
        #printableCard .status-badge { font-size: 6pt; padding: 1pt 5pt; }
        #printableCard .qr-code-box { padding: 2mm; border-radius: 2mm; }
        #printableCard .qr-code-box svg { width: 16mm !important; height: 16mm !important; }
        #printableCard .qr-hint { font-size: 5pt; }
        #printableCard .id-card-footer { padding: 2mm; font-size: 5pt; letter-spacing: 0.5pt; }

        /* Preserve colors in print */
        #printableCard .id-card-header-bg,
        #printableCard .id-card-footer,
        #printableCard .id-photo-placeholder,
        #printableCard .status-active,
        #printableCard .status-inactive,
        #printableCard .status-on-leave,
        #printableCard .status-terminated {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection
