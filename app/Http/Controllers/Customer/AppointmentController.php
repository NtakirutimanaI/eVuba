<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::where('user_id', Auth::id())->with('employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('employee', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('scheduled_at', 'desc')->paginate(10);

        $employees = Employee::all();

        return view('customer.appointments.index', compact('appointments', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('customer.appointments.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'employee_id' => 'nullable|exists:employees,id'
        ]);

        $employeeId = $request->employee_id;

        if (!$employeeId) {
            $employee = Employee::whereDoesntHave('appointments', function ($q) use ($request) {
                $q->where('scheduled_at', $request->scheduled_at);
            })->inRandomOrder()->first();

            $employeeId = $employee ? $employee->id : null;
        }

        Appointment::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
            'employee_id' => $employeeId,
        ]);

        return redirect()->route('customer.appointments.index')
            ->with('success', 'Appointment created successfully! Assigned Employee: ' . 
                ($employeeId ? Employee::find($employeeId)->name : 'Pending'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_at' => 'required|date',
        ]);

        $appointment->update([
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return redirect()->route('customer.appointments.index')
                         ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();

        return redirect()->route('customer.appointments.index')
            ->with('success', 'Appointment cancelled.');
    }

    public function suggestSchedule()
    {
        $availableEmployees = Employee::withCount(['appointments as future_appointments_count' => function($q) {
            $q->where('scheduled_at', '>=', now());
        }])->orderBy('future_appointments_count')->get();

        $suggestedEmployee = $availableEmployees->first();

        $nextAvailableTime = now()->addHour();
        while (Appointment::where('employee_id', $suggestedEmployee->id)
                     ->where('scheduled_at', $nextAvailableTime)
                     ->exists()) {
            $nextAvailableTime->addHour();
        }

        return response()->json([
            'employee' => $suggestedEmployee->name ?? 'No available employee',
            'suggested_time' => $nextAvailableTime->toDateTimeString(),
        ]);
    }
}
