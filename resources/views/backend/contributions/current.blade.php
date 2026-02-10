@extends('backend.master')

@section('content')
<h2>Current Month Contribution</h2>

@if($contribution)
    <p>Month: {{ $contribution->month }}/{{ $contribution->year }}</p>
    <p>Amount Due: KES {{ $contribution->amount_due }}</p>
    <p>Penalty: KES {{ $contribution->penalty }}</p>
    <p>Total: KES {{ $contribution->total_amount }}</p>
    <p>Status: {{ strtoupper($contribution->status) }}</p>
@else
    <p>No contribution record for this month yet.</p>
@endif
@endsection
