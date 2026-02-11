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
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="POST" id="deleteForm" class="text-center">
            @csrf
            @method('DELETE')
            <div class="modal-content position-relative">
                <button type="button" class="close position-absolute" style="top: 10px; right: 10px;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header justify-content-center border-0">
                <h5 class="modal-title w-100">Delete Contribution</h5>
                </div>
                <div class="modal-body">
                Are you sure you want to delete this contribution?
                </div>
                <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
            </form>
        </div>
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

<script>
$(document).on('click', '.deleteBtn', function () {
    let id = $(this).data('id');
    let url = "{{ route('backend.admin.contributions.delete', ':id') }}";
    url = url.replace(':id', id);

    $('#deleteForm').attr('action', url);

    // Use Bootstrap 4 modal API
    $('#deleteModal').modal('show');
});
</script>
@endpush
