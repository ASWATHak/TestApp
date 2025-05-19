@extends('layouts.app')

@section('content')
<h2>Tasks Lists</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Go to Dashboard</a>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Assign New Task</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee</th><th>Title</th><th>Status</th><th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->employee->user->name }}</td>
                    <td>{{ $task->title }}</td>
                    <td>
                        <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in progress" {{ $task->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $task->due_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
