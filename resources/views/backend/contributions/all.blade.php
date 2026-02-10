@extends('backend.master')

@section('title', 'Contributions')

@section('content')
<div class="card">
    <div class="card-body p-2 p-md-4 pt-0">
        <div class="table-responsive">
            <table id="datatables" class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Month / Year</th>
                        <th>Amount Due</th>
                        <th>Penalty</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(function() {
    $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.contributions.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'user', name: 'user' },
            { data: 'month', name: 'month' },
            { data: 'amount_due', name: 'amount_due' },
            { data: 'penalty', name: 'penalty' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ]
    });
});
</script>
@endpush
