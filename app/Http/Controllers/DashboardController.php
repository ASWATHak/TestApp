<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->employee) {
            // Employee view: Only see tasks assigned to them
            $tasks = Task::with('employee')->where('employee_id', $user->employee->id)->get();
        } else {
            // Admin/Manager view: See all tasks
            $tasks = Task::with('employee')->get();
        }

        return view('dashboard', compact('tasks'));
    }
}
