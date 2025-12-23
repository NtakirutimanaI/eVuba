<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 

        // Totals
        $teamMembersQuery = Employee::query();
        $teamCount = $teamMembersQuery->count();
        
        $ticketsCount = Ticket::count();
        $bookingsCount = Booking::count();
        $reportsCount = Order::count(); // "Orders" in manager context
        $appointmentsCount = Appointment::count();

        // Stock Alerts
        $lowStockItems = Product::all()->filter(function($p) {
            return $p->remaining_stock < 10;
        })->take(5);

        // Recent Activity
        $recentTeam = $teamMembersQuery->orderBy('created_at', 'desc')->take(5)->get();
        $recentTickets = Ticket::with(['customer', 'category'])->latest()->take(5)->get();
        $recentBookings = Booking::with(['user', 'employee', 'service'])->latest()->take(5)->get();
        $recentReports = Order::latest()->take(5)->get();
        $recentAppointments = Appointment::latest()->take(5)->get();

        // Graphs Data
        // Ticket Status Distribution
        $supportStatusData = Ticket::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();
        $ticketStatusLabels = $supportStatusData->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray();
        $ticketStatusCounts = $supportStatusData->pluck('total')->toArray();

        // Bookings per Employee
        $bookingEmployees = Employee::take(10)->get();
        $bookingLabels = $bookingEmployees->pluck('name')->toArray();
        $bookingCounts = $bookingEmployees->map(fn($emp) => Booking::where('employee_id', $emp->id)->count())->toArray();

        // Appointment Status Distribution
        $appointmentsData = Appointment::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();
        $appointmentStatusLabels = $appointmentsData->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray();
        $appointmentStatusCounts = $appointmentsData->pluck('total')->toArray();

        return view('manager.dashboard', compact(
            'teamCount',
            'ticketsCount',
            'bookingsCount',
            'reportsCount',
            'appointmentsCount',
            'lowStockItems',
            'recentTeam',
            'recentTickets',
            'recentBookings',
            'recentReports',
            'recentAppointments',
            'ticketStatusLabels',
            'ticketStatusCounts',
            'bookingLabels',
            'bookingCounts',
            'appointmentStatusLabels',
            'appointmentStatusCounts'
        ));
    }
}
