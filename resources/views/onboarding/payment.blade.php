@extends('layouts.plain')

@section('title', 'Complete Payment')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); font-family: 'Inter', sans-serif; }
    .plain-container { max-width: 860px; }

    .step-bar { display: flex; align-items: center; justify-content: center; margin-bottom: 2.5rem; }
    .step-item { display: flex; flex-direction: column; align-items: center; position: relative; flex: 1; }
    .step-item:not(:last-child)::after {
        content: ''; position: absolute; top: 16px; left: 50%; width: 100%;
        height: 2px; background: rgba(255,255,255,0.15); z-index: 0;
    }
    .step-item.done::after { background: rgba(16,185,129,0.5); }
    .step-circle {
        width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; font-size: 0.8rem; font-weight: 700; z-index: 1;
        border: 2px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3);
    }
    .step-item.active .step-circle { border-color: #6366f1; background: #6366f1; color: #fff; box-shadow: 0 0 18px rgba(99,102,241,0.6); }
    .step-item.done .step-circle   { border-color: #10b981; background: #10b981; color: #fff; }
    .step-label { font-size: 0.7rem; margin-top: 6px; color: rgba(255,255,255,0.35); font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
    .step-item.active .step-label  { color: #a5b4fc; }
    .step-item.done .step-label    { color: #6ee7b7; }

    .payment-card {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 24px; padding: 2.5rem; backdrop-filter: blur(20px);
    }
    .section-title { color: #fff; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; }
    .section-sub   { color: rgba(255,255,255,0.45); font-size: 0.9rem; }

    .payment-method {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px; padding: 1.2rem 1.4rem; margin-bottom: 0.8rem;
        display: flex; align-items: center; gap: 1rem; cursor: pointer;
        transition: all 0.25s;
    }
    .payment-method:hover, .payment-method.active {
        border-color: #6366f1; background: rgba(99,102,241,0.08);
    }
    .method-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; background: rgba(255,255,255,0.07); }
    .method-name { color: #fff; font-weight: 600; font-size: 0.95rem; }
    .method-desc { color: rgba(255,255,255,0.4); font-size: 0.78rem; }

    .price-summary {
        background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2);
        border-radius: 14px; padding: 1.4rem;
    }
    .price-row { display: flex; justify-content: space-between; color: rgba(255,255,255,0.6); font-size: 0.9rem; padding: 0.3rem 0; }
    .price-row.total { color: #fff; font-weight: 800; font-size: 1.1rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 0.5rem; padding-top: 0.7rem; }

    .qr-box {
        background: #fff; border-radius: 16px; padding: 0.75rem;
        display: flex; flex-direction: column; align-items: center;
        width: fit-content; margin: 0 auto;
        box-shadow: 0 4px 24px rgba(0,0,0,0.25);
    }
    .qr-box img {
        width: 220px; height: 220px;
        object-fit: contain; border-radius: 8px; display: block;
    }
    .qr-download {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: rgba(255,255,255,0.35); font-size: 0.75rem;
        text-decoration: none; margin-top: 0.5rem;
        transition: color 0.2s;
    }
    .qr-download:hover { color: #a5b4fc; }

    .upload-zone {
        border: 2px dashed rgba(99,102,241,0.4); border-radius: 14px;
        padding: 2rem; text-align: center; transition: all 0.3s; cursor: pointer;
        background: rgba(99,102,241,0.03);
    }
    .upload-zone:hover, .upload-zone.drag-over { border-color: #6366f1; background: rgba(99,102,241,0.08); }
    .upload-zone i   { color: rgba(255,255,255,0.25); font-size: 2.5rem; margin-bottom: 0.8rem; }
    .upload-zone p   { color: rgba(255,255,255,0.45); font-size: 0.85rem; margin: 0; }
    .upload-zone p strong { color: #a5b4fc; }

    .file-preview { display: none; align-items: center; gap: 0.8rem; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); border-radius: 10px; padding: 0.8rem 1rem; margin-top: 0.8rem; }
    .file-preview i { color: #34d399; font-size: 1.3rem; }
    .file-preview span { color: #6ee7b7; font-size: 0.85rem; font-weight: 600; }

    .btn-submit {
        background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; color: #fff;
        padding: 0.9rem; width: 100%; border-radius: 50px; font-weight: 700; font-size: 1rem;
        cursor: pointer; box-shadow: 0 4px 20px rgba(99,102,241,0.4); transition: all 0.3s;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99,102,241,0.5); }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .bank-info { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 1rem 1.2rem; }
    .bank-row  { display: flex; justify-content: space-between; align-items: center; padding: 0.35rem 0; }
    .bank-label { color: rgba(255,255,255,0.4); font-size: 0.8rem; }
    .bank-value { color: #fff; font-size: 0.88rem; font-weight: 600; }
    .copy-btn   { background: none; border: none; color: #818cf8; cursor: pointer; font-size: 0.8rem; padding: 2px 6px; border-radius: 4px; }
    .copy-btn:hover { background: rgba(99,102,241,0.15); }

    .plan-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3);
        color: #a5b4fc; padding: 0.3rem 0.9rem; border-radius: 50px;
        font-size: 0.78rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
    }
</style>
@endpush

@section('content')

{{-- Step Bar --}}
<div class="step-bar">
    <div class="step-item done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Welcome</div>
    </div>
    <div class="step-item done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:0.75rem;"></i></div>
        <div class="step-label">Choose Plan</div>
    </div>
    <div class="step-item active">
        <div class="step-circle">3</div>
        <div class="step-label">Payment</div>
    </div>
    <div class="step-item">
        <div class="step-circle">4</div>
        <div class="step-label">Pending</div>
    </div>
</div>

<div class="payment-card">
    <div class="row g-4">

        {{-- LEFT: QR + Bank Info --}}
        <div class="col-md-5">
            <div class="text-center mb-3">
                <div class="plan-badge"><i class="fa-solid fa-tag"></i> {{ $plan }} Plan</div>
            </div>

            <div class="qr-box mb-2">
                <img src="{{ asset('images/khqr_payment.png') }}"
                     alt="KHQR Payment QR Code — SmartHR"
                     id="qrImage">
            </div>
            <div class="text-center">
                <a href="{{ asset('images/khqr_payment.png') }}" download="SmartHR_KHQR.png" class="qr-download">
                    <i class="fa-solid fa-download"></i> Save QR image
                </a>
            </div>

            <div class="text-center mb-3">
                <div style="color: rgba(255,255,255,0.5); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.07em;">Scan with ABA · Bakong · KHQR App</div>
            </div>

            <div class="bank-info">
                <div class="bank-row">
                    <span class="bank-label">Bank</span>
                    <span class="bank-value">ABA Bank</span>
                </div>
                <div class="bank-row">
                    <span class="bank-label">Account Name</span>
                    <span class="bank-value">SmartHR Co., Ltd.</span>
                </div>
                <div class="bank-row">
                    <span class="bank-label">Account Number</span>
                    <div class="d-flex align-items-center gap-1">
                        <span class="bank-value" id="accNo">000-123-456</span>
                        <button class="copy-btn" onclick="copyText('000-123-456')"><i class="fa-solid fa-copy"></i></button>
                    </div>
                </div>
                <div class="bank-row">
                    <span class="bank-label">Reference</span>
                    <div class="d-flex align-items-center gap-1">
                        <span class="bank-value" id="ref">SMARTHR-{{ auth()->user()->company_id }}</span>
                        <button class="copy-btn" onclick="copyText('SMARTHR-{{ auth()->user()->company_id }}')"><i class="fa-solid fa-copy"></i></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Price Summary + Upload --}}
        <div class="col-md-7">
            <div class="section-title mb-1">Complete Your Payment</div>
            <p class="section-sub mb-3">Transfer the amount below, then upload your receipt.</p>

            <div class="price-summary mb-4">
                <div class="price-row"><span>{{ $plan }} Plan (1 Year)</span><span>${{ $price }}.00</span></div>
                <div class="price-row"><span>Setup Fee</span><span>Free</span></div>
                <div class="price-row total"><span>Total Due</span><span>${{ $price }}.00 USD</span></div>
            </div>

            {{-- Steps --}}
            <div class="mb-3" style="color: rgba(255,255,255,0.55); font-size: 0.83rem;">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <div style="background:#6366f1;color:#fff;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;margin-top:1px;">1</div>
                    <span>Open your banking app (ABA, Bakong, Wing, or any KHQR app)</span>
                </div>
                <div class="d-flex align-items-start gap-2 mb-2">
                    <div style="background:#6366f1;color:#fff;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;margin-top:1px;">2</div>
                    <span>Scan the QR code or transfer to account number manually</span>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <div style="background:#6366f1;color:#fff;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;margin-top:1px;">3</div>
                    <span>Take a screenshot of the transaction receipt and upload below</span>
                </div>
            </div>

            {{-- Upload Form --}}
            <form action="{{ route('onboarding.payment.submit') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="file" name="receipt" id="receiptInput" class="d-none" accept="image/*">

                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('receiptInput').click()">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><strong>Click to upload</strong> or drag & drop your receipt</p>
                    <p style="font-size: 0.75rem; margin-top: 0.3rem;">JPEG, PNG — Max 4MB</p>
                </div>

                <div class="file-preview" id="filePreview">
                    <i class="fa-solid fa-file-image"></i>
                    <span id="fileName">receipt.png</span>
                </div>

                @error('receipt')
                    <div class="alert alert-danger mt-2 py-2 small">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-submit mt-3" id="submitBtn" disabled>
                    <i class="fa-solid fa-paper-plane me-2"></i>Submit Payment Receipt
                </button>
            </form>

            <div class="text-center mt-3">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none" style="color: rgba(255,255,255,0.3); font-size: 0.8rem;">
                        <i class="fa-solid fa-arrow-left me-1"></i>Logout & return later
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const input = document.getElementById('receiptInput');
    const zone  = document.getElementById('uploadZone');
    const preview = document.getElementById('filePreview');
    const nameEl  = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');

    input.addEventListener('change', function () {
        if (this.files[0]) {
            nameEl.textContent = this.files[0].name;
            preview.style.display = 'flex';
            submitBtn.disabled = false;
        }
    });

    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag-over');
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
    });

    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            const btn = event.currentTarget;
            btn.innerHTML = '<i class="fa-solid fa-check"></i>';
            setTimeout(() => btn.innerHTML = '<i class="fa-solid fa-copy"></i>', 2000);
        });
    }
</script>
@endpush

@endsection
