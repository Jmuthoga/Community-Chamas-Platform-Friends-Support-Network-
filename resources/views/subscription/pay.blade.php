@extends('backend.master')

@section('title', 'POS Subscription')

@section('content')
<style>
    body {
        background: #f4f7fb;
        font-family: 'Inter', sans-serif;
    }

    .subscription-container {
        max-width: 520px;
        margin: 7vh auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
    }

    .subscription-header {
        text-align: center;
        background: linear-gradient(135deg, #0070ba, #003087);
        color: #fff;
        padding: 40px 20px 30px;
    }

    .subscription-header h3 {
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .subscription-body {
        padding: 35px 40px 25px;
    }

    .plan-options {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .plan-card {
        flex: 1;
        border: 1px solid #e3ebf3;
        border-radius: 12px;
        text-align: center;
        padding: 15px;
        cursor: pointer;
        background: #fafbfd;
        transition: all 0.3s ease;
    }

    .plan-card:hover {
        border-color: #0070ba;
        transform: translateY(-2px);
        background: #f0f8ff;
    }

    .plan-card.selected {
        border: 2px solid #0070ba;
        background: #e6f0ff;
    }

    .plan-card.demo {
        background: #e8fff3;
        border: 2px dashed #28a745;
    }

    .plan-card.demo h5 {
        color: #28a745;
    }

    .amount-display {
        text-align: center;
        background: #f8fafc;
        border-radius: 10px;
        padding: 20px;
        margin: 25px 0;
    }

    .btn-pay {
        background: #0070ba;
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 13px 0;
        font-weight: 600;
        width: 100%;
        transition: 0.3s ease;
    }

    .btn-pay:hover {
        background: #003087;
    }

    .stk-message {
        text-align: center;
        margin-top: 15px;
        padding: 12px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.9rem;
        display: none;
    }

    .stk-message.active {
        display: block;
    }

    .stk-message.info {
        background: #e7f3ff;
        color: #0070ba;
    }

    .stk-message.success {
        background: #eafaf1;
        color: #28a745;
    }

    .stk-message.error {
        background: #fdecea;
        color: #dc3545;
    }

    .spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #0070ba;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-right: 6px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .subscription-footer {
        text-align: center;
        font-size: 0.85rem;
        color: #777;
        border-top: 1px solid #f0f2f5;
        padding: 15px;
        background: #fafbfc;
    }

    /* Overlay styles */
    #paymentOverlay {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 30px 25px;
        border-radius: 15px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
        text-align: center;
        z-index: 9999;
        width: 90%;
        max-width: 400px;
    }

    #paymentOverlay .spinner {
        width: 2rem;
        height: 2rem;
        border-width: 3px;
    }
</style>

<div class="subscription-container">
    <div class="subscription-header">
        <h3><i class="bi bi-credit-card-2-front me-1"></i> POS Subscription</h3>
        <p>Renew or activate your POS system securely via M-Pesa</p>
    </div>

    <div class="subscription-body">
        <form id="subscriptionForm" action="{{ route('subscription.initiate') }}" method="POST">
            @csrf
            <label class="mb-2">Choose Subscription Duration</label>
            <div class="plan-options">
                <div class="plan-card demo" id="demoPlan">
                    <h5>Demo</h5>
                    <small>Free Trial (Auto Activate)</small>
                </div>
                <div class="plan-card selected" data-months="1">
                    <h5>1 Month</h5><small>KES 500</small>
                </div>
                <div class="plan-card" data-months="3">
                    <h5>3 Months</h5><small>KES 1500</small>
                </div>
                <div class="plan-card" data-months="6">
                    <h5>6 Months</h5><small>KES 3000</small>
                </div>
                <div class="plan-card" data-months="12">
                    <h5>1 Year</h5><small>KES 6000</small>
                </div>
            </div>

            <input type="hidden" name="months" id="months" value="1">

            <div class="mt-4" id="mpesaPhoneWrapper">
                <label for="phone">M-Pesa Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g. 2547XXXXXXXX" required>
            </div>

            <div class="amount-display">
                <small class="text-muted">Total Amount</small>
                <h2 id="total">KES 500</h2>
            </div>

            <button type="submit" class="btn btn-pay"><i class="bi bi-phone me-1"></i> Pay with M-Pesa</button>

            <div id="stkMessage" class="stk-message info"><span id="stkText"></span></div>
        </form>

        <!-- Overlay for payment progress -->
        <div id="paymentOverlay"></div>
    </div>

    <div class="subscription-footer">
        <i class="bi bi-shield-lock-fill text-success me-1"></i>
        Secure payments powered by
        <span style="font-weight:600; color:#0070ba;">M-Pesa</span>
    </div>
</div>

<script>
    const demoPlan = document.getElementById('demoPlan');
    const plans = document.querySelectorAll('.plan-card');
    const monthsInput = document.getElementById('months');
    const totalDisplay = document.getElementById('total');
    const stkMessage = document.getElementById('stkMessage');
    const stkText = document.getElementById('stkText');
    const form = document.getElementById('subscriptionForm');
    const phoneWrapper = document.getElementById('mpesaPhoneWrapper');
    const overlay = document.getElementById('paymentOverlay');

    function updateAmount(months) {
        totalDisplay.textContent = months === 0 ? 'Free Demo' : 'KES ' + (months * 500).toLocaleString();
    }

    // Plan selection
    plans.forEach(plan => {
        plan.addEventListener('click', () => {
            if (plan.classList.contains('demo')) return;
            if (demoPlan) demoPlan.classList.remove('selected');
            plans.forEach(p => p.classList.remove('selected'));
            plan.classList.add('selected');
            monthsInput.value = plan.getAttribute('data-months');
            updateAmount(monthsInput.value);
            phoneWrapper.style.display = 'block';
        });
    });

    // Demo activation
    if (demoPlan) {
        demoPlan.addEventListener('click', () => {
            plans.forEach(p => p.classList.remove('selected'));
            demoPlan.classList.add('selected');
            phoneWrapper.style.display = 'none';
            monthsInput.value = 0;
            updateAmount(0);
            stkMessage.classList.add('active', 'info');
            stkMessage.classList.remove('success', 'error');
            stkText.innerHTML = '<span class="spinner"></span> Activating Demo...';
            fetch("{{ route('subscription.demo') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            }).then(r => r.json()).then(data => {
                if (data.status === 'success') {
                    stkMessage.classList.remove('info');
                    stkMessage.classList.add('success');
                    stkText.innerHTML = '✅ Demo Mode activated! Redirecting...';
                    setTimeout(() => window.location.href = '/admin', 1200);
                } else {
                    stkMessage.classList.remove('info');
                    stkMessage.classList.add('error');
                    stkText.textContent = data.message || 'Failed to activate demo.';
                }
            }).catch(err => {
                stkMessage.classList.remove('info');
                stkMessage.classList.add('error');
                stkText.textContent = 'Error activating demo. Try again.';
                console.error(err);
            });
        });
    }

    // STK PUSH
    form.addEventListener('submit', e => {
        e.preventDefault();
        if (monthsInput.value == 0) return;
        stkMessage.classList.add('active', 'info');
        stkMessage.classList.remove('success', 'error');
        stkText.innerHTML = '<span class="spinner"></span> Sending STK Push...';

        fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') {
                    stkMessage.classList.remove('info');
                    stkMessage.classList.add('error');
                    stkText.textContent = data.message || 'Failed to send STK Push.';
                    return;
                }

                const checkoutId = data.checkout_id.trim();
                overlay.style.display = 'block';
                let seconds = 120;
                overlay.innerHTML = `<div class="spinner"></div><p>STK Push sent! Waiting (${seconds}s)...</p>`;

                const countdown = setInterval(() => {
                    seconds--;
                    overlay.innerHTML = `<div class="spinner"></div><p>STK Push sent! Waiting (${seconds}s)...</p>`;
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        overlay.innerHTML = '<p style="color:#dc3545;">Payment not confirmed. Try again.</p>';
                    }
                }, 1000);

                const poll = setInterval(() => {
                    fetch(`/api/subscription/check-status/${checkoutId}`).then(r => r.json()).then(resp => {
                        if (resp.status === 'active') {
                            clearInterval(poll);
                            clearInterval(countdown);
                            overlay.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 52 52" style="margin-bottom:15px;">
                            <circle cx="26" cy="26" r="25" fill="none" stroke="#28a745" stroke-width="2"/>
                            <path fill="none" stroke="#28a745" stroke-width="4" d="M14 27l7 7 16-16">
                                <animate attributeName="stroke-dasharray" from="0,50" to="50,0" dur="0.6s" fill="freeze" />
                            </path>
                        </svg>
                        <h3 style="color:#28a745;margin-bottom:8px;">Payment Successful!</h3>
                        <p style="font-size:0.95rem;margin:0;">Your subscription for <b>${resp.months} month(s)</b> is active.</p>
                        <p style="font-size:0.9rem;color:#555;margin:5px 0 12px;">Valid from <b>${resp.start_date}</b> to <b>${resp.expire_date}</b></p>
                        <button id="continueBtn" class="btn btn-success" style="padding:10px 25px;font-weight:600;border-radius:8px;margin-top:10px;">Continue</button>
                        <p style="font-size:0.85rem;color:#777;margin-top:8px;">Redirecting automatically in <span id="countdown">30</span> seconds...</p>
                    `;
                            let remaining = 30;
                            const cd = document.getElementById('countdown');
                            const redirectTimer = setInterval(() => {
                                remaining--;
                                cd.textContent = remaining;
                                if (remaining <= 0) {
                                    clearInterval(redirectTimer);
                                    window.location.href = '/admin';
                                }
                            }, 1000);
                            document.getElementById('continueBtn').addEventListener('click', () => {
                                clearInterval(redirectTimer);
                                overlay.style.display = 'none';
                                window.location.href = '/admin';
                            });
                        }
                    }).catch(err => console.error(err));
                }, 5000);
            }).catch(err => {
                stkMessage.classList.remove('info');
                stkMessage.classList.add('error');
                stkText.textContent = 'Error sending STK Push.';
                console.error(err);
            });
    });
</script>
@endsection