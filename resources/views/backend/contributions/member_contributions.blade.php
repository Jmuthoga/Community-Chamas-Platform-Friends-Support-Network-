@extends('backend.master')
@section('title', 'Contributions')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Contributions of {{ $user->name }}</h3>
            @can('view-contribution-view')
            <a href="{{ route('backend.admin.contributions.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-ruler-vertical"></i> All Contributions
            </a>
            @endcan
        </div>

        <div class="card-body">
            <p>Total Contributed: <strong>{{ currency()->symbol ?? '' }} {{ number_format($totalContributed, 2) }}</strong></p>

            <!-- table-responsive wrapper -->
            <div class="table-responsive">
                <table class="table table-bordered" id="contributionsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Month / Year</th>
                            <th>Amount Due</th>
                            <th>Penalty</th>
                            <th>Total Paid</th>
                            <th>Payment Type</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th>Transaction (Safaricom MPESA)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    let table = $('#contributionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.contributions.member.view', $user->id) }}?ajax=1",
        scrollX: true,          // horizontal scroll
        scrollCollapse: true,   // shrink table if few rows
        fixedHeader: true,      // keep header fixed while scrolling
        dom: '<"top text-center"lf>rt<"bottom text-center"ip>', // center search, length, pagination
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
            { data: 'month_year', name: 'month_year' },
            { data: 'amount_due', name: 'amount_due' },
            { data: 'penalty', name: 'penalty' },
            { data: 'total_paid', name: 'total_paid' },
            { data: 'payment_type', name: 'payment_type' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'mpesa_transaction', name: 'mpesa_transaction', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        language: { emptyTable: "No contributions found" }
    });
});
</script>
@endpush