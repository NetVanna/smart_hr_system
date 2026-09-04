@extends('layouts.app')

@section('title', __('messages.generate_payroll'))

@section('content')
<div class="container-fluid p-0" style="max-width: 860px;">
    {{-- Header --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1 text-dark">
            <a href="{{ route('payrolls.index') }}" class="text-muted text-decoration-none me-2" style="font-size: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> {{ __('messages.payrolls') }}
            </a>/ {{ __('messages.generate_payroll') }}
        </h3>
        <p class="text-muted small mb-0">Generate a payslip with USD & KHR dual-currency preview</p>
    </div>

    <div class="row g-4">
        {{-- Form --}}
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('payrolls.store') }}" method="POST" id="payrollForm">
                        @csrf

                        {{-- Employee Select --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('messages.employees') }} <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employeeSelect" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_employee') }}</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        data-salary="{{ $emp->salary }}"
                                        {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->employee_id }} — {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div id="empInfoBadge" class="mt-2" style="display: none;">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2" id="empInfoText" style="font-size: 0.78rem;"></span>
                            </div>
                        </div>

                        {{-- Month & Year --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.month') }} <span class="text-danger">*</span></label>
                                <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                                        <option value="{{ $m }}" {{ old('month', date('F')) == $m ? 'selected' : '' }}>
                                            {{ __('messages.' . strtolower($m)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.year') }} <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', date('Y')) }}" required>
                                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Salary Fields --}}
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.basic_salary') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="0.01" name="basic_salary" id="basicSalary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', 0) }}" required oninput="updatePreview()">
                                </div>
                                @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.allowance') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="0.01" name="allowance" id="allowanceInput" class="form-control @error('allowance') is-invalid @enderror" value="{{ old('allowance', 0) }}" required oninput="updatePreview()">
                                </div>
                                @error('allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('messages.deductions') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="0.01" name="deductions" id="deductionsInput" class="form-control @error('deductions') is-invalid @enderror" value="{{ old('deductions', 0) }}" required oninput="updatePreview()">
                                </div>
                                @error('deductions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- NSSF Helper --}}
                        <div class="alert alert-info d-flex align-items-center gap-2 py-2 px-3 mb-4" style="border-radius: 10px; font-size: 0.82rem;">
                            <i class="fa-solid fa-circle-info text-info"></i>
                            <span>Cambodia NSSF deduction: <strong>2% employee</strong> + <strong>3.3% healthcare</strong> of basic salary. 
                            <button type="button" class="btn btn-sm btn-link p-0 ms-1 text-info" onclick="autoNssf()" style="font-size: 0.82rem;">Auto-calculate NSSF</button>
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-file-invoice-dollar me-1"></i> {{ __('messages.generate_payroll') }}
                            </button>
                            <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live Dual-Currency Preview --}}
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-eye text-primary"></i> Live Payslip Preview
                    </h6>
                    <small class="text-muted">Exchange Rate: <strong>{{ number_format($exchangeRate, 0) }} KHR/USD</strong></small>
                </div>
                <div class="card-body px-4 pb-4">
                    {{-- USD Preview --}}
                    <div class="p-3 rounded-4 mb-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-primary small">USD (Dollar)</span>
                            <span class="badge bg-primary" style="font-size: 0.7rem;">$</span>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Basic</small>
                                <strong class="text-dark" id="prevBasicUSD">$0.00</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Allowance</small>
                                <strong class="text-success" id="prevAllowanceUSD">+$0.00</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Deduction</small>
                                <strong class="text-danger" id="prevDeductionUSD">-$0.00</strong>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Net Salary (USD)</span>
                            <span class="fs-5 fw-bold text-primary" id="prevNetUSD">$0.00</span>
                        </div>
                    </div>

                    {{-- KHR Preview --}}
                    <div class="p-3 rounded-4" style="background: linear-gradient(135deg, #fff7ed, #ffedd5);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-orange small" style="color: #c2410c;">KHR (Riel)</span>
                            <span class="badge" style="font-size: 0.7rem; background: #f97316;">៛</span>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Basic</small>
                                <strong class="text-dark" id="prevBasicKHR" style="font-size: 0.82rem;">0 ៛</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Allowance</small>
                                <strong style="font-size: 0.82rem; color: #16a34a;" id="prevAllowanceKHR">0 ៛</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Deduction</small>
                                <strong style="font-size: 0.82rem; color: #dc2626;" id="prevDeductionKHR">0 ៛</strong>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Net Salary (KHR)</span>
                            <span class="fs-6 fw-bold" style="color: #c2410c;" id="prevNetKHR">0 ៛</span>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <small class="text-muted" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-info-circle me-1"></i> Preview updates live as you type
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const EXCHANGE_RATE = {{ $exchangeRate }};

    function fmt(val) {
        return parseFloat(val || 0).toFixed(2);
    }
    function fmtKHR(usdVal) {
        return Math.round(usdVal * EXCHANGE_RATE).toLocaleString();
    }

    function updatePreview() {
        const basic      = parseFloat(document.getElementById('basicSalary').value) || 0;
        const allowance  = parseFloat(document.getElementById('allowanceInput').value) || 0;
        const deductions = parseFloat(document.getElementById('deductionsInput').value) || 0;
        const net        = basic + allowance - deductions;

        document.getElementById('prevBasicUSD').textContent     = '$' + fmt(basic);
        document.getElementById('prevAllowanceUSD').textContent = '+$' + fmt(allowance);
        document.getElementById('prevDeductionUSD').textContent = '-$' + fmt(deductions);
        document.getElementById('prevNetUSD').textContent       = '$' + fmt(net);

        document.getElementById('prevBasicKHR').textContent     = fmtKHR(basic) + ' ៛';
        document.getElementById('prevAllowanceKHR').textContent = fmtKHR(allowance) + ' ៛';
        document.getElementById('prevDeductionKHR').textContent = fmtKHR(deductions) + ' ៛';
        document.getElementById('prevNetKHR').textContent       = fmtKHR(net) + ' ៛';
    }

    function autoNssf() {
        const basic      = parseFloat(document.getElementById('basicSalary').value) || 0;
        // NSSF: 2% employee contribution + 3.3% healthcare = 5.3% total
        const nssf       = parseFloat((basic * 0.053).toFixed(2));
        document.getElementById('deductionsInput').value = nssf;
        updatePreview();
    }

    // Auto-fill salary when employee is selected (use data attribute)
    document.getElementById('employeeSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const salary   = parseFloat(selected.getAttribute('data-salary')) || 0;
        const empName  = selected.text;

        if (salary > 0) {
            document.getElementById('basicSalary').value = salary.toFixed(2);
            document.getElementById('empInfoText').textContent = '✓ Auto-filled salary from employee profile: $' + salary.toFixed(2);
            document.getElementById('empInfoBadge').style.display = '';
        } else {
            document.getElementById('empInfoBadge').style.display = 'none';
        }
        updatePreview();
    });

    // Init on page load
    updatePreview();
</script>
@endsection
