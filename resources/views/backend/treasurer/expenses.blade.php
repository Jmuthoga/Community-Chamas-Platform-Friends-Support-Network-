@extends('backend.master')

@section('title', 'Expenses')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Expenses</h3>
        @can('treasurer_expenses_create')
        <a href="{{ route('backend.admin.treasurer.expenses.create') }}" class="btn bg-gradient-primary btn-sm">
            <i class="fas fa-plus-circle"></i> Add Expense
        </a>
        @endcan
        <form id="filterForm" class="form-inline ml-3">
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
            url: "{{ route('backend.admin.treasurer.expenses') }}",
            data: function(d) {
                d.month = $('#filterMonth').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
            { data: 'member', name: 'member' },
            { data: 'amount', name: 'amount' },
            { data: 'date', name: 'expense_date' },
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