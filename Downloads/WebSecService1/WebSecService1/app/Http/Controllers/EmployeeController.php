<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-employees');
        $employees = User::role('employee')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        Gate::authorize('manage-employees');
        return view('employees.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-employees');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $employee = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
        ]);

        $employee->assignRole('employee');

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(User $employee)
    {
        Gate::authorize('manage-employees');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        Gate::authorize('manage-employees');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->phone = $validated['phone'] ?? null;
        
        if (!empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->save();

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        Gate::authorize('manage-employees');
        
        if ($employee->hasRole('employee')) {
            $employee->delete();
            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted successfully.');
        }

        return redirect()->route('employees.index')
            ->with('error', 'Cannot delete this user.');
    }
} 