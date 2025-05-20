<form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" class="d-flex align-items-center">
    @csrf
    <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
    </select>
</form>
