@extends('layouts.app')

@section('title', __('messages.payroll_management'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('messages.payroll_management') }}</h2>
    <a href="{{ route('payrolls.create') }}" class="btn btn-primary"><i class="fa-solid fa-file-invoice-dollar"></i> {{ __('messages.generate_payroll') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 ps-4">{{ __('messages.employees') }}</th>
                    <th class="border-0">{{ __('messages.month_year') }}</th>
                    <th class="border-0">{{ __('messages.net_salary') }}</th>
                    <th class="border-0 pe-4 text-end">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                <tr>
                    <td class="ps-4">
                        <strong>{{ optional($payroll->employee)->first_name }} {{ optional($payroll->employee)->last_name }}</strong><br>
                        <small class="text-muted">{{ optional($payroll->employee)->employee_id }}</small>
                    </td>
                    <td>{{ $payroll->month }} {{ $payroll->year }}</td>
                    <td><strong>{{ \App\Helpers\CurrencyHelper::dualDisplay($payroll->net_salary) }}</strong></td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('payrolls.show', $payroll) }}" target="_blank" class="btn btn-sm btn-outline-info" title="{{ __('messages.download_payslip') }}"><i class="fa-solid fa-download"></i> PDF</a>
                        <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_payroll_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('messages.delete') }}"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">{{ __('messages.no_payrolls_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
