<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('employee.user')->get();
        return view('tasks.index', compact('tasks'));
    }
    
public function getData(Request $request)
{
    $tasks = Task::select('tasks.*', 'users.name as employee_name')
        ->join('employees', 'tasks.employee_id', '=', 'employees.id')
        ->join('users', 'employees.user_id', '=', 'users.id');

    return DataTables::of($tasks)
        ->addColumn('employee', function ($task) {
            return $task->employee_name ?? 'N/A';
        })
        ->addColumn('status', function ($task) {
            return view('tasks.partials.status-select', ['task' => $task])->render();
        })
        ->orderColumn('employee', 'users.name $1')
        ->filterColumn('employee', function ($query, $keyword) {
            $query->where('users.name', 'like', "%{$keyword}%");
        })
        ->rawColumns(['status'])
        ->make(true);
}

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
        ]);

        Task::create([
            'employee_id' => $request->employee_id,
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'status'      => 'pending',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task assigned successfully.');
    }

    // Add this method for updating task status
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in progress,completed',
        ]);
        $task->status = $request->status;
        $task->save();
        return redirect()->back()->with('success', 'Task status updated successfully.');
    }
}
