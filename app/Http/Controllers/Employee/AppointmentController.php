<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments assigned to the logged-in employee,
     * with optional search across multiple columns.
     */
    public function index(Request $request)
    {
        $employeeId = Auth::id();
        $query = Appointment::where('employee_id', $employeeId)->with([
            'user' => function ($q) {
                $q->withTrashed();
            }
        ]);

        // Live search across multiple columns
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $appointments = $query->orderBy('scheduled_at', 'asc')->paginate(10);

        return view('employee.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment (optional).
     */
    public function create()
    {
        return view('employee.appointments.create');
    }

    /**
     * Store a newly created appointment (optional, if employees can create).
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after_or_equal:now',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        Appointment::create([
            'user_id' => $request->user_id,
            'employee_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'status' => $request->status,
        ]);

        return redirect()->route('employee.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display a single appointment assigned to the logged-in employee.
     */
    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        return view('employee.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing an appointment.
     */
    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        return view('employee.appointments.edit', compact('appointment'));
    }

    /**
     * Update an appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after_or_equal:now',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'status' => $request->status,
        ]);

        return redirect()->route('employee.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete an appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $appointment->delete();

        return redirect()->route('employee.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Ensure employee can only access their own appointments.
     */
    private function authorizeAppointment(Appointment $appointment)
    {
        if ($appointment->employee_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
    // Add this method to Employee\AppointmentController

    public function help(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'message' => 'required|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        if ($appointment->employee_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->update(['feedback' => $request->message]);

        return redirect()->route('employee.appointments.index')
            ->with('success', 'Feedback sent to customer successfully.');
    }

    /**
     * AJAX: Get details for a specific appointment.
     */
    public function getDetails($id)
    {
        // Load 'customer' relationship explicitly along with 'user'
        $appointment = Appointment::with(['customer', 'user' => fn($q) => $q->withTrashed()])->findOrFail($id);

        $this->authorizeAppointment($appointment);

        // Prioritize Customer model (Business Data), fallback to User model (Auth Data)
        $customer = $appointment->customer ?? $appointment->user;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'description' => $appointment->description,
                'status' => $appointment->status,
                'scheduled_at' => $appointment->scheduled_at?->format('M d, Y h:i A') ?? 'N/A',
                'customer_name' => $customer?->name ?? 'Guest/Unknown',
                'customer_email' => $customer?->email ?? 'N/A',
                'customer_phone' => $customer?->phone ?? 'Not Provided',
                'customer_address' => $customer?->address ?? 'Not Provided',
            ]
        ]);
    }

    /**
     * AJAX: Update appointment status.
     */
    public function ajaxUpdateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->authorizeAppointment($appointment);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . ucfirst($request->status)
        ]);
    }

}
