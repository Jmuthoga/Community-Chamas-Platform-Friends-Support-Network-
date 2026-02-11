@extends('backend.master')

@section('title','Contribution Payment History')

@section('content')
<div class="container mt-4">

    <h3>
        Payments for {{ $contribution->month }} / {{ $contribution->year }}
    </h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Amount</th>
                <th>Paid At</th>
            </tr>
        </thead>

        <tbody>
        @foreach($payments as $key => $payment)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ number_format($payment->amount,2) }}</td>
                <td>{{ $payment->paid_at?->format('d M Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection
