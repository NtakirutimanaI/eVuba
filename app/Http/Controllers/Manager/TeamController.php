<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee; // Use Employee model

class TeamController extends Controller
{
    public function index()
    {
        // Get all employees
        $employees = Employee::all();
        return view('manager.team.index', compact('employees'));
    }

    public function create()
    {
        return view('manager.team.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'position'   => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        Employee::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'position'       => $request->position,
            'specialization' => $request->specialization,
            'department'     => $request->department,
        ]);

        return redirect()->route('manager.team.index')
                         ->with('success', 'Employee added successfully.');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('manager.team.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'position'   => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $employee->update([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'position'       => $request->position,
            'specialization' => $request->specialization,
            'department'     => $request->department,
        ]);

        return redirect()->route('manager.team.index')
                         ->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('manager.team.index')
                         ->with('success', 'Employee deleted successfully.');
    }
    
}
