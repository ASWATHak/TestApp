<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user.role')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        $latestEmployee = Employee::orderBy('id', 'desc')->first();
        if ($latestEmployee && preg_match('/EMP(\d+)/', $latestEmployee->empId, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
    
        $empId = 'EMP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    
        Employee::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'empId' => $empId,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }
}
