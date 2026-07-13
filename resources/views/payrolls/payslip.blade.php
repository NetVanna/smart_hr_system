<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.payslip') }} - {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', 'Kantumruy Pro', sans-serif; 
            font-size: 14px; 
            color: #333; 
        }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 28px; }
        .company-name { font-size: 18px; color: #555; margin-top: 5px; }
        .details-table, .salary-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table td { padding: 8px; vertical-align: top; }
        .salary-table th, .salary-table td { border: 1px solid #ddd; padding: 12px; }
        .salary-table th { background-color: #f8f9fa; text-align: left; }
        .amount { text-align: right !important; }
        .total-row { font-weight: bold; background-color: #e9ecef !important; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('messages.payslip') }}</h1>
        <div class="company-name">{{ optional($payroll->employee->company)->name ?? 'SmartHR SaaS' }}</div>
        <div>{{ __('messages.for_month_of') }} {{ __('messages.' . strtolower($payroll->month)) }} {{ $payroll->year }}</div>
    </div>

    <table class="details-table">
        <tr>
            <td width="50%">
                <strong>{{ __('messages.employee_id') }}:</strong> {{ $payroll->employee->employee_id }}<br>
                <strong>{{ __('messages.employee_name') }}:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}<br>
                <strong>{{ __('messages.email') }}:</strong> {{ $payroll->employee->email ?? 'N/A' }}
            </td>
            <td width="50%" style="text-align: right;">
                <strong>{{ __('messages.department') ?? 'Department' }}:</strong> {{ optional($payroll->employee->department)->department_name ?? 'N/A' }}<br>
                <strong>{{ __('messages.job_title') ?? 'Job Title' }}:</strong> {{ $payroll->employee->job_title ?? 'N/A' }}<br>
                <strong>{{ __('messages.date_generated') }}:</strong> {{ \Carbon\Carbon::parse($payroll->created_at)->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    <table class="salary-table">
        <thead>
            <tr>
                <th>{{ __('messages.description') ?? 'Description' }}</th>
                <th class="amount">{{ __('messages.amount_usd') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('messages.basic_salary') }}</td>
                <td class="amount">${{ number_format($payroll->basic_salary, 2) }}</td>
            </tr>
            <tr>
                <td>{{ __('messages.allowance') }}</td>
                <td class="amount">${{ number_format($payroll->allowance, 2) }}</td>
            </tr>
            <tr>
                <td>{{ __('messages.deductions') }}</td>
                <td class="amount">-${{ number_format($payroll->deductions, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>{{ strtoupper(__('messages.net_salary')) }}</td>
                <td class="amount">${{ number_format($payroll->net_salary, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        {{ __('messages.payslip_footer') }}
    </div>
</body>
</html>
