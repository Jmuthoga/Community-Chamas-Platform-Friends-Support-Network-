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
                    <p><strong id="balanceDisplay">{{ number_format($contribution->total_amount - $contribution->paid_amount, 2) }}</strong></p>
                </div>

                {{-- Payment Type --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Payment Type *</label>
                    <select name="payment_type" class="form-control" id="paymentType" required>
                        <option value="installment">Installment</option>
                        <option value="full">Full Payment</option>
                    </select>
                </div>

                {{-- Payment Amount --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Payment Amount *</label>
                    <input type="number"
                           class="form-control"
                           name="amount"
                           id="paymentAmount"
                           min="1"
                           max="{{ $contribution->total_amount - $contribution->paid_amount }}"
                           value="{{ $contribution->total_amount - $contribution->paid_amount }}"
                           required>
                    <small class="text-muted" id="amountHint">
                        You can pay in installment or full. Max: {{ number_format($contribution->total_amount - $contribution->paid_amount, 2) }}
                    </small>
                </div>

                {{-- Payment Method --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Payment Method</label>
                    <input type="text" class="form-control" value="Cash" readonly>
                </div>

                {{-- Submit Button --}}
                <div class="col-md-6 mt-4">
                    <button type="submit" class="btn btn-success">
                        Confirm Payment
                    </button>
                </div>

            </div>
        </form>

        {{-- Show installment history for current month --}}
        @if($contribution->payments->count())
        <hr>
        <h5>Payment History for {{ $contribution->month }} / {{ $contribution->year }}</h5>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Amount</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contribution->payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->paid_at->format('d M Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    </div>
</div>

@endsection

@push('script')
<script>
    const paymentType = document.getElementById('paymentType');
    const paymentAmount = document.getElementById('paymentAmount');
    const balance = {{ $contribution->total_amount - $contribution->paid_amount }};
    const amountHint = document.getElementById('amountHint');

    paymentType.addEventListener('change', function () {
        if (this.value === 'full') {
            // Full payment: amount must equal balance
            paymentAmount.value = balance;
            paymentAmount.min = balance;
            paymentAmount.max = balance;
            amountHint.textContent = "Full payment selected. You must pay the full remaining balance: " + balance.toFixed(2);
        } else {
            // Installment: any amount up to balance
            paymentAmount.value = 1;
            paymentAmount.min = 1;
            paymentAmount.max = balance;
            amountHint.textContent = "Installment selected. You can pay any amount from 1 up to remaining balance: " + balance.toFixed(2);
        }
    });
</script>
@endpush

