<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Report;
use App\Models\Appointment;
use App\Models\Sale;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Counts
        $tasksCount = Task::where('user_id', $userId)->count();
        $reportsCount = Report::where('user_id', $userId)->count();
        $appointmentsCount = Appointment::where('employee_id', $userId)->count();

        $salesCount = Sale::where('user_id', $userId)->sum('total_amount'); 
        $ticketsResolvedCount = Ticket::where('assigned_to', $userId) // Using assigned_to as per migration
                                      ->where('status', 'resolved')
                                      ->count();

        // Recent items
        $recentTasks = Task::where('user_id', $userId)->latest()->take(5)->get();
        $recentReports = Report::where('user_id', $userId)->latest()->take(5)->get();
        $recentAppointments = Appointment::where('employee_id', $userId)
                                         ->orderBy('scheduled_at', 'asc')
                                         ->take(5)
                                         ->get();
        $recentSales = Sale::where('user_id', $userId)->with('product')->latest()->take(5)->get();

        // Performance Charts
        // Sales over last 14 days
        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('user_id', $userId)
        ->where('created_at', '>=', Carbon::now()->subDays(14))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $performanceLabels = $salesData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray();
        $performanceValues = $salesData->pluck('total')->toArray();

        // Task Status Distribution
        $taskStatusData = Task::select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->groupBy('status')
            ->get();
        
        $taskLabels = $taskStatusData->pluck('status')->map(fn($s) => ucfirst($s))->toArray();
        $taskCounts = $taskStatusData->pluck('total')->toArray();

        return view('employee.dashboard', compact(
            'tasksCount',
            'reportsCount',
            'appointmentsCount',
            'salesCount',
            'ticketsResolvedCount',
            'recentTasks',
            'recentReports',
            'recentAppointments',
            'recentSales',
            'performanceLabels',
            'performanceValues',
            'taskLabels',
            'taskCounts'
        ));
    }
}
