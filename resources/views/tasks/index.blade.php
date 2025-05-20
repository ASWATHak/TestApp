@extends('layouts.app')

@section('content')
<h2>Tasks Lists</h2>
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Go to Dashboard</a>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Assign New Task</a>
</div>

<table class="table table-bordered" id="tasks-table">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Title</th>
            <th>Status</th>
            <th>Due Date</th>
        </tr>
    </thead>
</table>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tasks-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("tasks.data") }}',
        columns: [
            { data: 'employee', name: 'employee', orderable:true, searchable:true },
            { data: 'title', name: 'title', orderable:true, searchable:true },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'due_date', name: 'due_date', orderable: false, searchable: false },
        ],
    });
});
</script>
@endpush
