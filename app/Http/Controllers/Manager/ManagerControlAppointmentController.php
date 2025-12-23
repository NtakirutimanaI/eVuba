<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppointmentsExport;

class ManagerControlAppointmentController extends Controller
{
    /**
     * Display a listing of appointments with search and filter functionality.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'employee']);

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('scheduled_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Search Intelligence
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('scheduled_at', 'desc')->paginate(10)->withQueryString();
        $employees = User::where('role', 'employee')->get();

        // Operational Intelligence Stats
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'assigned' => Appointment::where('status', 'assigned')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
        ];

        // Appointment Pulse (Last 7 Days)
        $chartData = Appointment::selectRaw('DATE(scheduled_at) as date, COUNT(*) as count')
            ->where('scheduled_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('manager.appointments.index', compact('appointments', 'employees', 'stats', 'chartData'));
    }

    /**
     * Show a single appointment details.
     */
    public function show($id)
    {
        $appointment = Appointment::with(['user', 'employee'])->findOrFail($id);
        $employees = User::where('role', 'employee')->get();

        return view('manager.appointments.show', compact('appointment', 'employees'));
    }

    /**
     * Assign an employee to an appointment (by ID).
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->employee_id = $request->employee_id;
        $appointment->status = 'assigned';
        $appointment->save();

        return back()->with('success', 'Employee assigned successfully.');
    }

    /**
     * Assign employee to appointment (using route-model binding).
     */
    public function assignEmployee(Request $request, Appointment $appointment)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id', // employees are users with role='employee'
        ]);

        $appointment->employee_id = $request->employee_id; // save users.id
        $appointment->save();

        return redirect()->back()->with('success', 'Employee assigned successfully.');
    }

    /**
     * Update appointment status (completed, canceled, etc.).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $validStatuses = ['pending', 'confirmed', 'completed', 'canceled', 'assigned'];
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'Invalid status value.');
        }

        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Mark appointment as completed.
     */
    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'completed';
        $appointment->save();

        return back()->with('success', 'Appointment marked as completed.');
    }

    /**
     * Delete an appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Export appointments as PDF or Excel.
     */
    public function export(Request $request)
    {
        $type = $request->query('type', 'pdf');
        $query = Appointment::with(['user', 'employee']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('scheduled_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $appointments = $query->orderBy('scheduled_at', 'desc')->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('manager.appointments.export', compact('appointments'));
            return $pdf->download('appointments.pdf');
        }

        if ($type === 'excel') {
            return Excel::download(new AppointmentsExport($appointments), 'appointments.xlsx');
        }

        return back()->with('error', 'Invalid export type.');
    }
}
