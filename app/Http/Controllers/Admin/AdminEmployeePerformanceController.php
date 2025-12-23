<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\Appointment;
use App\Models\Ticket;
use App\Models\Sale;
use App\Models\Task;
use Carbon\Carbon;
use DB;

class AdminEmployeePerformanceController extends Controller
{
    /**
     * Display all employees' performance metrics.
     */
    public function index()
    {
        $employees = Employee::with('user')->get();

        // 1. Detailed Performance Matrix
        $performance = $employees->map(function($employee) {
            $userId = $employee->id;
            
            // Core Metrics
            $bookingsCount = Booking::where('employee_id', $userId)->count();
            $appointmentsCount = Appointment::where('employee_id', $userId)->count();
            $salesTotal = Sale::where('user_id', $userId)->sum('total_amount');
            $ticketsResolved = Ticket::where('assigned_to', $userId)->where('status', 'resolved')->count();
            
            // Task Metrics
            $tasksAssigned = Task::where('user_id', $userId)->count();
            $tasksCompleted = Task::where('user_id', $userId)->where('status', 'completed')->count();
            $taskCompletionRate = $tasksAssigned > 0 ? round(($tasksCompleted / $tasksAssigned) * 100, 1) : 0;

            // Efficiency
            $avgResponse = Ticket::where('assigned_to', $userId)
                                ->where('status', 'resolved')
                                ->avg(DB::raw('TIMESTAMPDIFF(HOUR, created_at, updated_at)'));

            return [
                'id'                => $userId,
                'name'              => $employee->name,
                'position'          => $employee->position,
                'bookings'          => $bookingsCount,
                'appointments'      => $appointmentsCount,
                'sales'             => (float)$salesTotal,
                'tickets_resolved'  => $ticketsResolved,
                'tasks_completed'   => $tasksCompleted,
                'task_rate'         => $taskCompletionRate,
                'avg_response'      => round($avgResponse ?? 0, 1),
                'total_score'       => ($bookingsCount * 10) + ($salesTotal / 1000) + ($ticketsResolved * 5) + ($tasksCompleted * 2)
            ];
        })->sortByDesc('total_score')->values();

        // 2. Sales Trends (Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }

        $salesTrends = $employees->map(function($emp) use ($months) {
            $data = $months->map(function($month) use ($emp) {
                return Sale::where('user_id', $emp->id)
                    ->whereMonth('created_at', Carbon::parse($month)->month)
                    ->whereYear('created_at', Carbon::parse($month)->year)
                    ->sum('total_amount');
            });

            return [
                'name' => $emp->name,
                'data' => $data
            ];
        });

        // 3. Overall Activity Distribution
        $activityDistribution = [
            'labels' => ['Bookings', 'Appointments', 'Tasks', 'Tickets'],
            'data'   => [
                Booking::count(),
                Appointment::count(),
                Task::count(),
                Ticket::count()
            ]
        ];

        return view('admin.performance.index', compact(
            'performance', 
            'salesTrends', 
            'months', 
            'activityDistribution'
        ));
    }
}
