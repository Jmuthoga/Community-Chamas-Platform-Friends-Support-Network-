@extends('backend.master')

@section('title', 'All Transactions')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Transactions</h3>
        <form id="filterForm" class="form-inline">
            <input type="month" id="filterMonth" class="form-control form-control-sm mr-2">
            <button type="button" id="filterBtn" class="btn btn-sm bg-gradient-primary">Filter</button>
            <button type="button" id="clearFilter" class="btn btn-sm bg-gradient-secondary ml-2">Clear</button>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table id="datatables" class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member</th>
                    <th>Amount (KES)</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
$(function() {
    let table = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('backend.admin.treasurer.transactions') }}",
            data: function(d) {
                d.month = $('#filterMonth').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
            { data: 'member', name: 'member' },
            { data: 'amount', name: 'amount' },
            { data: 'date', name: 'transaction_date' },
            { data: 'type', name: 'type' },
            { data: 'payment_method', name: 'payment_method' },
        ]
    });

    $('#filterBtn').click(function() {
        table.draw();
    });

    $('#clearFilter').click(function() {
        $('#filterMonth').val('');
        table.draw();
    });
});
</script>
@endpush