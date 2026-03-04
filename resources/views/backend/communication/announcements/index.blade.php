@extends('backend.master')

@section('title', 'All Announcements')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-bullhorn mr-2"></i> All Announcements</h3>
        @can('announcement_create')
        <a href="{{ route('backend.admin.communications.announcements.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus-circle"></i> Create Announcement
        </a>
        @endcan
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="announcements-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Audience</th>
                    <th>Email</th>
                    <th>SMS</th>
                    <th>Status</th>
                    <th>Creator</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('script')
<script>
$(function() {
    $('#announcements-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("backend.admin.communications.announcements") }}',
        order: [[8, 'desc']], // <-- use 'created_at' column (10th column, index 9)
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'message', name: 'message' },
            { data: 'audience', name: 'audience' },
            { data: 'send_email', name: 'send_email' },
            { data: 'send_sms', name: 'send_sms' },
            { data: 'is_active', name: 'is_active' },
            { data: 'creator', name: 'creator' },
            { data: 'created_at', name: 'created_at' }, // real DB column
            { data: 'actions', orderable: false, searchable: false },
        ]
    });

    // Delete button
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        if(confirm('Are you sure you want to delete this announcement?')) {
            $.ajax({
                url: '{{ route("backend.admin.communications.announcements.delete", ":id") }}'.replace(':id', id),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    alert(res.success);
                    $('#announcements-table').DataTable().ajax.reload();
                }
            });
        }
    });
});
</script>
@endpush
@endsection