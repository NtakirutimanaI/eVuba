<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Appointment;
use App\Models\Sale;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use DB;

class EmployeePerformanceController extends Controller
{
    /**
     * Display the employee performance dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the currently logged-in user (employee)
        $user = Auth::user();

        // Calculate performance metrics
        $bookings = Booking::where('employee_id', $user->id)->count();
        $appointments = Appointment::where('employee_id', $user->id)->count();
        $sales = Sale::where('user_id', $user->id)->sum('total_amount');
        $ticketsResolved = Ticket::where('assigned_to', $user->id)
                                 ->where('status', 'closed')
                                 ->count();
        $ticketsTotal = Ticket::where('assigned_to', $user->id)->count();

        // Average response time in hours for closed tickets
        $avgResponseTime = Ticket::where('assigned_to', $user->id)
                                 ->where('status', 'closed')
                                 ->join('ticket_logs', 'tickets.id', '=', 'ticket_logs.ticket_id')
                                 ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, ticket_logs.created_at, tickets.updated_at)) as avg_hours'))
                                 ->value('avg_hours');

        // Additional calculated metrics
        $bookingsAppointmentsRatio = $appointments > 0 ? round($bookings / $appointments, 2) : $bookings;
        $salesPerTicket = $ticketsResolved > 0 ? round($sales / $ticketsResolved, 2) : $sales;

        $performance = [
            'bookings' => $bookings,
            'appointments' => $appointments,
            'sales' => $sales,
            'tickets_resolved' => $ticketsResolved,
            'tickets_total' => $ticketsTotal,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'bookings_appointments_ratio' => $bookingsAppointmentsRatio,
            'sales_per_ticket' => $salesPerTicket,
        ];

        return view('employee.performance.index', compact('performance'));
    }
}
