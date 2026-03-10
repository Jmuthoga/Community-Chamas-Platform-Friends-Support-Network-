@extends('backend.master')

@section('title', 'All Events')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-calendar-alt mr-2"></i> All Events
        </h3>

        @can('event_create')
        <a href="{{ route('backend.admin.communications.events.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus-circle"></i> Create Event
        </a>
        @endcan
    </div>

    <div class="card-body">
        <!-- Add table-responsive wrapper -->
        <div class="table-responsive">
            <table class="table table-bordered" id="events-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Time</th>
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
</div>

@push('script')
<script>
$(function() {
    let table = $('#events-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("backend.admin.communications.events") }}',
        order: [[10, 'desc']], // created_at column
        scrollX: true,         // horizontal scroll
        scrollCollapse: true,  // shrink table if few rows
        fixedHeader: true,     // keeps search & length fixed
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title' },
            { data: 'description' },
            { data: 'location' },
            { data: 'event_date' },
            { data: 'event_time' },
            { data: 'send_email' },
            { data: 'send_sms' },
            { data: 'is_active' },
            { data: 'creator' },
            { data: 'created_at' },
            { data: 'actions', orderable: false, searchable: false },
        ]
    });

    // Delete button
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        if(confirm('Are you sure you want to delete this event?')) {
            $.ajax({
                url: '{{ route("backend.admin.communications.events.delete", ":id") }}'.replace(':id', id),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    alert(res.success);
                    table.ajax.reload();
                }
            });
        }
    });
});
</script>
@endpush
@endsection