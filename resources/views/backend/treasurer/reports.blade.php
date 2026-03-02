@extends('backend.master')

@section('title', 'Financial Reports')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Monthly Income</h4></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Income (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyIncome as $income)
                        <tr>
                            <td>{{ \Carbon\Carbon::create()->month($income->month)->format('F') }}</td>
                            <td>{{ number_format($income->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Monthly Expenses</h4></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Expenses (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyExpenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::create()->month($expense->month)->format('F') }}</td>
                            <td>{{ number_format($expense->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection