<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user.role')->get();
        return view('employees.index', compact('employees'));
    }
    public function getData(Request $request)
    {
        $employees = Employee::with('user.role')
            ->select('employees.*')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id');

    return DataTables::of($employees)
        ->addColumn('name', function ($employee) {
            return $employee->user->name;
        })
        ->addColumn('email', function ($employee) {
            return $employee->user->email;
        })
        ->addColumn('role', function ($employee) {
            return $employee->user->role->name ?? 'N/A';
        })
        ->orderColumn('name', 'users.name $1')
        ->orderColumn('email', 'users.email $1')
        ->orderColumn('role', 'roles.name $1')
        ->filterColumn('name', function ($query, $keyword) {
            $query->where('users.name', 'like', "%{$keyword}%");
        })
        ->filterColumn('email', function ($query, $keyword) {
            $query->where('users.email', 'like', "%{$keyword}%");
        })
        ->filterColumn('role', function ($query, $keyword) {
            $query->where('roles.name', 'like', "%{$keyword}%");
        })
        ->make(true);

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
