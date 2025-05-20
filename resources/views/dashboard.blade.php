@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-sm">Here’s a summary of your tasks.</p>
        </div>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <div class="d-flex justify-content-end mb-4 gap-2">
        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary">Employees List</a>
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-success">Tasks List</a>
    </div>

    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-sm">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">
            {{ auth()->user()->employee ? 'Your Assigned Tasks' : 'All Tasks' }}
        </h2>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($tasks as $task)
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow border dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ strtoupper($task->title) }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">{{ $task->description }}</p>

                <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <span><strong>📅 Due:</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                    @if(!auth()->user()->employee)
                        <span><strong>👤 Assigned:</strong> {{ $task->employee->name ?? 'N/A' }}</span>
                    @endif
                </div>

                @if(auth()->user()->employee)
                <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" class="mt-2">
                    @csrf
                    <select name="status" class="form-select form-select-sm w-100 mb-2" onchange="this.form.submit()">
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $task->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </form>
                @else
                <span class="inline-block text-xs px-3 py-1 rounded-full 
                    @if($task->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($task->status === 'in progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                    {{ ucfirst($task->status) }}
                </span>
                @endif
            </div>
            @empty
                <p class="text-gray-500 dark:text-gray-300 col-span-full text-center">No tasks assigned.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection