@extends('backend.master')

@section('title','My Contribution Payments')

@section('content')
<div class="card">

    {{-- Make Payment Button --}}
    @can('make-contribution-payment')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.contributions.payments.create') }}" 
           class="btn bg-gradient-success">
            <i class="fas fa-money-bill-wave"></i>
            Make Payment
        </a>
    </div>
    @endcan

    <div class="card-body p-2 p-md-4 pt-0">
        <div class="row g-4">
            <div class="col-md-12">
                <div class="card-body table-responsive p-0" id="table_data">
                    <table id="paymentsTable" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th data-orderable="false">#</th>
                                <th>Month / Year</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                                <th>Paid At</th>
                                <th data-orderable="false">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
$(function() {
    let table = $('#paymentsTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        order: [[1, 'desc']],
        ajax: {
            url: "{{ route('backend.admin.contributions.payments.index') }}"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable:false, orderable:false },
            { data: 'month_year', name: 'month_year' },
            { data: 'amount', name: 'amount' },
            { data: 'status', name: 'status' },
            { data: 'paid_at', name: 'paid_at' },
            { 
                data: 'contribution_id', 
                name: 'action', 
                render: function(data){
                    return `<a href="/admin/contributions/payments/${data}" 
                            class="btn btn-sm btn-primary">
                            View
                        </a>`;
                }
            },
        ]
    });
});
</script>
@endpush
