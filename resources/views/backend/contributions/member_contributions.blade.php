@extends('backend.master')

@section('content')
<div class="container mt-4">
    <h3>Contributions of {{ $user->name }}</h3>
    <p>Total Contributed: <strong>{{ currency()->symbol ?? '' }}  {{ number_format($totalContributed, 2) }}</strong></p>

    @can('view-contribution-view')
    <div class="mt-n5 mb-3 d-flex justify-content-end">
        <a href="{{ route('backend.admin.contributions.index') }}" class="btn bg-gradient-primary">
            <i class="fas fa-ruler-vertical"></i>
            All Contributions
        </a>
    </div>
    @endcan

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
                <th>Transaction (Safaricom MPESA)</th> {{-- NEW COLUMN --}}
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    $('#contributionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.contributions.member.view', $user->id) }}?ajax=1",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
            { data: 'month_year', name: 'month_year' },
            { data: 'amount_due', name: 'amount_due' },
            { data: 'penalty', name: 'penalty' },
            { data: 'total_paid', name: 'total_paid' },
            { data: 'payment_type', name: 'payment_type' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'mpesa_transaction', name: 'mpesa_transaction', orderable: false, searchable: false } // NEW
        ],
        order: [[1, 'asc']],
        language: { emptyTable: "No contributions found" }
    });
});
</script>
@endpush
