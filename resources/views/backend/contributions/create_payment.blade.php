@extends('backend.master')

@section('title','Make Contribution Payment')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('backend.admin.contributions.payments.pay') }}" method="POST" id="paymentForm">
            @csrf

            <div class="row">

                {{-- Contribution Info --}}
                <div class="mb-3 col-md-4">
                    <label class="form-label">Month</label>
                    <p>{{ $contribution->month }} / {{ $contribution->year }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <label class="form-label">Amount Due</label>
                    <p>{{ number_format($contribution->amount_due, 2) }}</p>
                </div>

                <div class="mb-3 col-md-4">
                    <label class="form-label">Penalty</label>
                    <p>{{ number_format($contribution->penalty, 2) }}</p>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Total Paid</label>
                    <p>{{ number_format($contribution->paid_amount, 2) }}</p>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Remaining Balance</label>
                    <p><strong id="balanceDisplay">
                        {{ number_format($contribution->total_amount - $contribution->paid_amount, 2) }}
                    </strong></p>
                </div>

                {{-- Payment Type --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Payment Type *</label>
                    <select name="payment_type" class="form-control" id="paymentType" required>
                        <option value="installment">Installment</option>
                        <option value="full">Full Payment</option>
                    </select>
                </div>

                {{-- Amount --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Payment Amount *</label>
                    <input type="number"
                           class="form-control"
                           name="amount"
                           id="paymentAmount"
                           min="1"
                           max="{{ $contribution->balance }}"
                           value="{{ $contribution->balance }}"
                           required>

                    <small class="text-muted" id="amountHint">
                        Max: {{ number_format($contribution->balance, 2) }}
                    </small>
                </div>

                {{-- Payment Method --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Payment Method *</label>
                    <select name="payment_method" class="form-control" id="paymentMethod" required>
                        <option value="mpesa" selected>MPESA</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>

                {{-- MPESA OPTIONS --}}
                <div class="col-md-6" id="mpesaOptions">

                    <label class="form-label">MPESA Phone Option</label>

                    <select class="form-control mb-2" id="phoneOption">
                        <option value="account">Use Account Phone ({{ auth()->user()->phone }})</option>
                        <option value="custom">Enter Different Phone</option>
                    </select>

                    <input type="text"
                           name="mpesa_phone"
                           id="mpesaPhone"
                           class="form-control d-none"
                           placeholder="Enter MPESA Number e.g 07XXXXXXXX">
                </div>

                {{-- Submit Buttons --}}
                <div class="col-md-6 mt-4">
                    <button type="submit" class="btn btn-success" id="confirmBtn">
                        Confirm Payment
                    </button>

                    <button type="button" class="btn btn-primary d-none" id="stkPushBtn">
                        Send STK Push
                    </button>
                </div>

            </div>
        </form>

{{-- Payment History --}}
@if($contribution->payments->count())
<hr>
<h5>Payment History</h5>

<table class="table table-bordered mt-2">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Paid At</th>
            <th>Transaction (Safaricom MPESA)</th>
        </tr>
    </thead>

    <tbody>
        @foreach($contribution->payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>
                    @if($payment->status == 'completed')
                        <span class="badge bg-success">Paid</span>
                    @elseif($payment->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-danger">Failed</span>
                    @endif
                </td>
                <td>{{ optional($payment->paid_at)->format('d M Y H:i:s') ?? '-' }}</td>
                <td>
                    @if($payment->mpesa_receipt)
                        <div style="display:flex; flex-direction:column; align-items:center; text-align:center;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/15/M-PESA_LOGO-01.svg" 
                                 alt="MPESA" width="80" style="object-fit:contain; margin-bottom:5px;">
                            <span style="font-weight:400; color:#000000;">{{ $payment->mpesa_receipt }}</span>
                        </div>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif



    </div>
</div>

{{-- MPESA STK MODAL --}}
<div class="modal fade" id="mpesaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">

            {{-- Close Button Bootstrap 4 --}}
            <button type="button" class="close position-absolute" style="top:10px; right:10px;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <img src="https://upload.wikimedia.org/wikipedia/commons/1/15/M-PESA_LOGO-01.svg"
                 width="100"
                 class="mx-auto d-block mb-3">

            <h5 id="stkTitle">Waiting for Payment</h5>

            <p class="text-muted">
                Requesting KES <strong id="modalAmount"></strong>
            </p>

            <div id="stkLoader">
                <div class="spinner-border text-success"></div>
                <p class="mt-2">Waiting for confirmation...</p>
            </div>

            <div id="stkSuccess" class="d-none text-center">

                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08.02l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 9.439 5.525 7.465a.75.75 0 0 0-1.06 1.06l2.505 2.505z"/>
                    </svg>
                </div>

                <h5 class="text-success">Payment Received</h5>

                <p>Amount: KES <span id="successAmount"></span></p>
                <p>Balance: KES <span id="successBalance"></span></p>
                <p>Phone: <span id="successPhone"></span></p>
                <p>Transaction ID: <span id="transactionId">-</span></p>

                {{-- Close Button Bootstrap 4 --}}
                <button class="btn btn-secondary mt-3" data-dismiss="modal" id="closeStkModal">
                    Close
                </button>

            </div>

        </div>
    </div>
</div>

{{-- PHONE INPUT MODAL --}}
<div class="modal fade" id="phoneModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">

            {{-- Close Button Bootstrap 4 --}}
            <button type="button" class="close position-absolute" style="top:10px; right:10px;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <h5>Enter Different MPESA Number</h5>

            <input type="text"
                   id="modalPhoneInput"
                   class="form-control mt-3"
                   placeholder="07XXXXXXXX">

            <button class="btn btn-success mt-3" id="savePhoneBtn">
                Save Number
            </button>

        </div>
    </div>
</div>

{{-- ERROR MODAL --}}
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-danger p-4">
      
      {{-- Close Button --}}
      <button type="button" class="close position-absolute" style="top:10px; right:10px;" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>

      <h5 class="text-danger" id="errorModalLabel">Payment Error</h5>
      <p id="errorMessage">An error occurred while processing your payment.</p>

      <button type="button" class="btn btn-danger mt-3" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>


@endsection

@push('script')
<script>
$(document).ready(function(){

    const paymentType = $('#paymentType');
    const paymentAmount = $('#paymentAmount');
    const paymentMethod = $('#paymentMethod');

    const mpesaOptions = $('#mpesaOptions');
    const phoneOption = $('#phoneOption');
    const mpesaPhone = $('#mpesaPhone');

    const balance = {{ $contribution->balance }};
    const amountHint = $('#amountHint');

    const confirmBtn = $('#confirmBtn');
    const stkPushBtn = $('#stkPushBtn');

    const mpesaModal = $('#mpesaModal');
    const phoneModal = $('#phoneModal');
    const errorModal = $('#errorModal');
    const errorMessageEl = $('#errorMessage');

    // ⭐ ONLY SAFE BOOTSTRAP FIX
    mpesaModal.on('hidden.bs.modal', function () {
        $('#stkLoader').removeClass('d-none');
        $('#stkSuccess').addClass('d-none');
    });

    // Initialize MPESA as default
    mpesaOptions.removeClass('d-none');
    confirmBtn.addClass('d-none');
    stkPushBtn.removeClass('d-none');

    // Payment type change
    paymentType.change(function(){
        if($(this).val() === 'full'){
            paymentAmount.val(balance);
            paymentAmount.attr('min', balance);
            paymentAmount.attr('max', balance);
            amountHint.text("Full payment required: " + balance.toFixed(2));
        } else {
            paymentAmount.val(1);
            paymentAmount.attr('min', 1);
            paymentAmount.attr('max', balance);
            amountHint.text("Installment allowed up to " + balance.toFixed(2));
        }
    });

    // Payment method toggle
    paymentMethod.change(function(){
        if($(this).val() === 'mpesa'){
            mpesaOptions.removeClass('d-none');
            confirmBtn.addClass('d-none');
            stkPushBtn.removeClass('d-none');
        } else {
            mpesaOptions.addClass('d-none');
            confirmBtn.removeClass('d-none');
            stkPushBtn.addClass('d-none');
        }
    });

    // Phone modal
    phoneOption.change(function(){
        if($(this).val() === 'custom'){
            phoneModal.modal('show'); // ⭐ KEEP SIMPLE
        }
    });

    // Save phone
    $('#savePhoneBtn').click(function(){
        const customPhone = $('#modalPhoneInput').val();

        if(customPhone){
            mpesaPhone.val(customPhone).removeClass('d-none');
            phoneModal.modal('hide');
        }
    });

// STK Push
stkPushBtn.on('click', async function(e) {

    e.preventDefault();
    e.stopPropagation();

    const phone = mpesaPhone.val() || "{{ auth()->user()->phone }}";
    const amount = parseFloat(paymentAmount.val());

    if (!amount || amount <= 0) {
        alert('Enter a valid payment amount');
        return;
    }

    try {

        const res = await fetch("{{ route('backend.admin.mpesa.stk.push') }}", {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({
                phone,
                amount,
                contribution_id: {{ $contribution->id }}
            })
        });

        const data = await res.json();

        if(res.ok && data.checkout_request_id){

            $('#modalAmount').text(amount.toFixed(2));
            mpesaModal.modal('show');

            const checkoutId = data.checkout_request_id;

            const interval = setInterval(async () => {

                try {

                    const statusRes = await fetch("{{ route('backend.admin.mpesa.check.status', ['checkoutId' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', checkoutId))
                    const status = await statusRes.json();

                    if(status.status === 'completed'){

                        clearInterval(interval);

                        $('#stkLoader').addClass('d-none');
                        $('#stkSuccess').removeClass('d-none');

                        $('#successAmount').text(amount.toFixed(2));
                        $('#successBalance').text(({{ $contribution->balance }} - amount).toFixed(2));
                        $('#successPhone').text(phone);
                        $('#transactionId').text(status.receipt || '-');
                    }

                    if(status.status === 'failed'){

                        clearInterval(interval);

                        mpesaModal.modal('hide');

                        setTimeout(function(){
                            errorMessageEl.text('Payment failed or cancelled.');
                            errorModal.modal('show');
                        }, 300);
                    }

                } catch(e) {
                    console.error(e);
                }

            }, 5000);

        } else {

            errorMessageEl.text(data.error || 'Failed to initiate STK Push.');
            errorModal.modal('show');
        }

    } catch(err){

        console.error(err);
        errorMessageEl.text('Error sending STK Push. Check console.');
        errorModal.modal('show');
    }

});

});

</script>
@endpush
