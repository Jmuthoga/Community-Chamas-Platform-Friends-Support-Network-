@extends('backend.master')

@section('title','Make Contribution Payment')

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('backend.admin.contributions.payments.pay') }}" method="POST">
@csrf

<div class="row">

    {{-- Contribution Info --}}
    <div class="mb-3 col-md-4">
        <label class="form-label">Month</label>
        <p>{{ $contribution->month }} / {{ $contribution->year }}</p>
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label">Amount Due</label>
        <p>{{ number_format($contribution->amount_due,2) }}</p>
    </div>

    <div class="mb-3 col-md-4">
        <label class="form-label">Total Paid</label>
        <p>{{ number_format($contribution->paid_amount,2) }}</p>
    </div>

    {{-- Payment Amount --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">Payment Amount *</label>

        <input type="number"
               class="form-control"
               name="amount"
               min="1"
               max="{{ $contribution->balance }}"
               required>
    </div>

    {{-- Payment Method --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">Payment Method</label>

        <input type="text" class="form-control" value="Cash" readonly>
    </div>

    <div class="col-md-6 mt-4">
        <button type="submit" class="btn btn-success">
            Confirm Payment
        </button>
    </div>

</div>
</form>

</div>
</div>

@endsection
