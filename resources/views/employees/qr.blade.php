@extends('layouts.app')

@section('title', __('messages.view_qr'))

@section('content')
<div class="no-print">
    <div class="row justify-content-center py-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <a href="{{ route('employees.index') }}" class="text-decoration-none text-muted">
                        <i class="fa-solid fa-arrow-left"></i> {{ __('messages.employees') }}
                    </a>
                    / {{ __('messages.view_qr') }}
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
    <div class="col-md-5">
        <div class="card shadow-sm text-center p-4" id="printableQr">
            <div class="card-body">
                <h4 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                <p class="text-muted mb-3">
                    {{ $employee->employee_id }}
                    @if($employee->department)
                        — {{ $employee->department->department_name }}
                    @endif
                </p>

                <div class="qr-box mb-3">
                    @if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode'))
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($employee->employee_id) !!}
                    @else
                        <div class="qr-fallback">
                            <i class="fa-solid fa-qrcode fa-5x text-muted"></i>
                            <small class="d-block mt-2">{{ $employee->employee_id }}</small>
                        </div>
                    @endif
                </div>

                <div class="d-grid gap-2 no-print">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fa-solid fa-print"></i> {{ __('messages.print_id') }}
                    </button>
                    <div class="text-muted small mt-2">{{ __('messages.scan_hint') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .qr-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        display: inline-block;
    }
    .qr-box svg {
        display: block;
    }
    .qr-fallback {
        width: 250px;
        height: 250px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        body {
            background: #fff !important;
        }

        nav, .navbar, .sidebar, .no-print, header, .btn, button {
            display: none !important;
        }

        #printableQr {
            box-shadow: none !important;
            border: 1px solid #ccc;
            width: 80mm;
            margin: 20mm auto;
        }

        #printableQr .qr-box svg {
            width: 50mm !important;
            height: 50mm !important;
        }
    }
</style>
@endsection
