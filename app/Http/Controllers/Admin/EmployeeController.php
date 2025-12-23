<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Appointment; // Make sure this exists
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display all employees with performance metrics
     */
    public function index()
    {
        $employees = Employee::orderBy('created_at', 'desc')->paginate(10);

        foreach ($employees as $employee) {
            $user = User::find($employee->id); // Assuming employee.id = user.id
            if ($user && method_exists($user, 'performanceMetrics')) {
                $metrics = $user->performanceMetrics();
                $employee->tasks_assigned  = $metrics['tasks_assigned'] ?? 0;
                $employee->tasks_completed = $metrics['tasks_completed'] ?? 0;
                $employee->tickets_handled = $metrics['tickets_handled'] ?? 0;
                $employee->tickets_resolved= $metrics['tickets_resolved'] ?? 0;
            } else {
                $employee->tasks_assigned  = 0;
                $employee->tasks_completed = 0;
                $employee->tickets_handled = 0;
                $employee->tickets_resolved= 0;
            }
        }

        // ====== Stats for Cards ======
        $stats = [
            'total' => Employee::count(),
            'departments' => Employee::whereNotNull('department')->distinct('department')->count(),
            'recent' => Employee::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.employee.index', compact('employees', 'stats'));
    }

    /**
     * Show form to create new employee
     */
    public function create()
    {
        return view('admin.employee.create');
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email', // Check users table!
            'phone'          => 'nullable|string|max:20',
            'position'       => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:150',
            'department'     => 'nullable|string|max:100',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 1. Create User Account first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make('password123'), // Default password
            'role' => 'employee',
        ]);

        // 2. Prepare Employee Data
        $data = $request->only(['name','email','phone','position','specialization','department']);
        $data['id'] = $user->id; // Force ID to match User ID

        // Handle optional image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('employees', 'public');
            $data['image'] = $imagePath;
        }

        // 3. Create Employee Record
        Employee::create($data);

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee created successfully (Linked to User ID: ' . $user->id . ').');
    }

    /**
     * Show form to edit employee
     */
    public function edit(Employee $employee)
    {
        return view('admin.employee.edit', compact('employee'));
    }

    /**
     * Update employee
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:employees,email,' . $employee->id,
            'phone'          => 'nullable|string|max:20',
            'position'       => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:150',
            'department'     => 'nullable|string|max:100',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name','email','phone','position','specialization','department']);

        // Handle optional image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($employee->image && Storage::disk('public')->exists($employee->image)) {
                Storage::disk('public')->delete($employee->image);
            }
            $imagePath = $request->file('image')->store('employees', 'public');
            $data['image'] = $imagePath;
        }

        $employee->update($data);

        // Sync and extend with User table
        $user = User::find($employee->id);
        if ($user) {
            $userUpdateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'status' => $request->input('status', $user->status),
            ];

            // Handle password update if provided
            if ($request->filled('password')) {
                $userUpdateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            $user->update($userUpdateData);
        }

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee and associated user account synchronized successfully.');
    }

    /**
     * Delete employee
     */
    public function destroy(Employee $employee)
    {
        if ($employee->image && Storage::disk('public')->exists($employee->image)) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
                         ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Show appointments assigned to a specific employee
     */
    public function appointments(Employee $employee)
    {
        $appointments = Appointment::where('employee_id', $employee->id)
                                   ->with('user')
                                   ->orderBy('scheduled_at', 'desc')
                                   ->get();

        return view('employee.appointments.index', compact('appointments', 'employee'));
    }

    /**
     * Provide Help for appointment (if AJAX/POST exists)
     */
    public function provideHelp(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'message'        => 'required|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->help_message = $request->message;
        $appointment->status = 'helped';
        $appointment->save();

        return redirect()->back()->with('success', 'Help provided successfully.');
    }

    /**
     * Save customer feedback (if AJAX/POST exists)
     */
    public function saveFeedback(Request $request)
    {
        $request->validate([
            'appointment_id'  => 'required|exists:appointments,id',
            'feedback_message'=> 'required|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->feedback_message = $request->feedback_message;
        $appointment->save();

        return redirect()->back()->with('success', 'Feedback saved successfully.');
    }
}
