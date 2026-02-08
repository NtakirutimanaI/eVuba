<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminAppointmentController extends Controller
{
    // Employee view of their appointments
    public function index()
    {
        $employeeId = auth()->id();
        $user = auth()->user();

        if ($user->role === 'employee') {
            $appointments = Appointment::where('employee_id', $employeeId)
                ->with('user')
                ->orderBy('scheduled_at', 'desc')
                ->paginate(5); // Pagination reduced to 5

            return view('employee.appointments.index', compact('appointments'));
        }

        $user = Auth::user();

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('Customer')) {
            return redirect()->route('customer.appointments.index');
        }

        $appointments = Appointment::with('user', 'employee')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(5); // Pagination reduced to 5

        $employees = User::where('role', 'employee')->get();
        $customers = User::where('role', 'customer')->get();

        return view('admin.appointments.index', compact('appointments', 'employees', 'customers'));
    }

    // ... (edit, update, showJson methods remain same, skipping to report)

    // Report
    public function generateReport(Request $request)
    {
        $query = Appointment::with('user', 'employee')->orderBy('scheduled_at', 'desc');

        // Apply filters
        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('scheduled_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('scheduled_at', '<=', $request->end_date);
        }

        $appointments = $query->get();

        $pdf = Pdf::loadView('admin.appointments.report', compact('appointments'));

        // Add date range to filename if present
        $filename = 'tasks_report';
        if ($request->start_date)
            $filename .= '_' . $request->start_date;

        return $pdf->download($filename . '.pdf');
    }

    // Excel Export
    public function exportExcel(Request $request)
    {
        $query = Appointment::with('user', 'employee')->orderBy('scheduled_at', 'desc');

        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('scheduled_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('scheduled_at', '<=', $request->end_date);
        }

        $appointments = $query->get();

        $filename = "tasks_export_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($appointments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Title', 'Description', 'Employee', 'Customer', 'Status', 'Priority', 'Date', 'Source']);

            foreach ($appointments as $task) {
                fputcsv($handle, [
                    $task->id,
                    $task->title,
                    $task->description,
                    $task->employee ? $task->employee->name : 'Unassigned',
                    $task->user ? $task->user->name : 'N/A',
                    $task->status,
                    $task->priority,
                    $task->scheduled_at,
                    $task->source_type . ($task->auto_assigned ? ' (Auto)' : '')
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    // Edit form
    public function edit(Appointment $appointment)
    {
        $employees = User::where('role', 'employee')->get();
        $customers = User::where('role', 'customer')->get();

        // Ensure relationships are loaded
        $appointment->load(['customer', 'user' => fn($q) => $q->withTrashed()]);

        return view('admin.appointments.edit', compact('appointment', 'employees', 'customers'));
    }

    // Update appointment
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'employee_id' => 'nullable|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $appointment->update([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'employee_id' => $request->employee_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'priority' => $request->priority ?? $appointment->priority ?? 'medium',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $appointment->load(['user', 'employee'])
            ]);
        }

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    // Update only status
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        // Notify Customer/User
        if ($appointment->user) {
            $appointment->user->notify(new \App\Notifications\SystemAlert([
                'title' => 'Appointment Status Updated',
                'message' => 'The status of your appointment "' . $appointment->title . '" has been updated to ' . ucfirst($appointment->status) . '.',
                'icon' => 'fa-calendar-check',
                'action_url' => '#'
            ]));
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    // Delete
    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
            }

            return redirect()->back()->with('success', 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete task.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to delete task.');
        }
    }

    // Assign employee
    public function assignEmployee(Request $request, Appointment $appointment)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
        ]);

        $appointment->employee_id = $request->employee_id;
        $appointment->save();

        // Notify Employee
        if ($appointment->employee) {
            $appointment->employee->notify(new \App\Notifications\SystemAlert([
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned to: ' . $appointment->title . '.',
                'icon' => 'fa-tasks',
                'action_url' => route('employee.appointments.index')
            ]));
        }

        return redirect()->back()->with('success', 'Employee assigned successfully.');
    }

    // Show appointment
    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }



    /**
     * Store a new task
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'employee_id' => 'nullable|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $appointment = Appointment::create([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'employee_id' => $request->employee_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'priority' => $request->priority ?? 'medium',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $appointment->load(['user', 'employee'])
            ]);
        }

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    /**
     * Get task details as JSON
     */
    public function showJson($id)
    {
        $appointment = Appointment::with(['customer', 'user' => fn($q) => $q->withTrashed(), 'employee'])->findOrFail($id);

        // Append dynamic attributes if needed, or rely on frontend to parse 'customer' object
        // We will just return the full model with relations
        return response()->json($appointment);
    }

    /**
     * Update task priority
     */
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update(['priority' => $request->priority]);

        return response()->json([
            'success' => true,
            'message' => 'Priority updated successfully'
        ]);
    }

    /**
     * Assign employee via AJAX
     */
    public function assignEmployeeAjax(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->employee_id = $request->employee_id;
        $appointment->save();

        // Notify Employee
        if ($appointment->employee) {
            $appointment->employee->notify(new \App\Notifications\SystemAlert([
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned to: ' . $appointment->title . '.',
                'icon' => 'fa-tasks',
                'action_url' => route('employee.appointments.index')
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee assigned successfully'
        ]);
    }
}
