<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\Appointment;
use App\Models\Task;
use App\Models\Ticket;
use DB;
use Carbon\Carbon;

class AdminEmployeePerformanceController extends Controller
{
    /**
     * Display all employees' performance metrics.
     */
    public function index()
    {
        $employees = Employee::with('user')->get();

        // 1. Detailed Performance Matrix
        $performance = $employees->map(function ($employee) {
            // Employee model uses user_id as its PK (non-incrementing).
            // We confirm via the user relation to get the actual users.id
            $userId = $employee->user ? $employee->user->id : $employee->id;

            // Bookings & Appointments assigned to this employee
            $bookingsCount = Booking::where('employee_id', $userId)->count();
            $appointmentsCount = Appointment::where('employee_id', $userId)->count();

            // Tickets resolved or completed by this employee
            $ticketsResolved = Ticket::where('assigned_to', $userId)
                ->whereIn('status', ['resolved', 'completed', 'closed'])
                ->count();

            // Task Metrics
            $tasksAssigned = Appointment::where('employee_id', $userId)
                ->where(function ($q) {
                    $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
                })->count();

            $activeTasks = Appointment::where('employee_id', $userId)
                ->where('status', '!=', 'completed')
                ->where(function ($q) {
                    $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
                })->count();

            $tasksCompleted = Appointment::where('employee_id', $userId)
                ->where('status', 'completed')
                ->where(function ($q) {
                    $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
                })->count();
            $taskCompletionRate = $tasksAssigned > 0
                ? round(($tasksCompleted / $tasksAssigned) * 100, 1)
                : 0;

            // Weighted composite score
            $score = ($bookingsCount * 10)
                + ($appointmentsCount * 5)
                + ($tasksCompleted * 8)
                + ($ticketsResolved * 6);

            return [
                'id' => $userId,
                'name' => $employee->name,
                'position' => $employee->position,
                'bookings' => $bookingsCount,
                'appointments' => $appointmentsCount,
                'tickets_resolved' => $ticketsResolved,
                'tasks_completed' => $tasksCompleted,
                'tasks_assigned' => $tasksAssigned,
                'active_tasks' => $activeTasks,
                'task_rate' => $taskCompletionRate,
                'total_score' => round($score, 0),
            ];
        })->sortByDesc('total_score')->values();

        // 2. Task Completion Trends (Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }

        $taskTrends = $employees->map(function ($emp) use ($months) {
            $userId = $emp->user ? $emp->user->id : $emp->id;
            $data = $months->map(function ($month) use ($userId) {
                return Appointment::where('employee_id', $userId)
                    ->where(function ($q) {
                        $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
                    })
                    ->where('status', 'completed')
                    ->whereMonth('updated_at', Carbon::parse($month)->month)
                    ->whereYear('updated_at', Carbon::parse($month)->year)
                    ->count();
            });

            return [
                'name' => $emp->name,
                'data' => $data,
            ];
        });

        // 3. Overall Activity Distribution
        $activityDistribution = [
            'labels' => ['Bookings', 'Appointments', 'Tasks Completed', 'Tasks Pending'],
            'data' => [
                Booking::count(),
                Appointment::where('source_type', 'customer_request')->count(),
                Appointment::where('status', 'completed')->where(function ($q) {
                    $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
                })->count(),
                Appointment::where('status', '!=', 'completed')->where(function ($q) {
                    $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
                })->count(),
            ],
        ];

        // 4. Detailed Active Tasks List (for new table)
        $activeTasksList = Appointment::with(['employee', 'user'])
            ->whereNotNull('employee_id')
            ->where('status', '!=', 'completed')
            ->where(function ($q) {
                $q->whereNull('source_type')->orWhere('source_type', '!=', 'customer_request');
            })
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10, ['*'], 'active_tasks_page');

        return view('admin.performance.index', compact(
            'performance',
            'taskTrends',
            'months',
            'activityDistribution',
            'activeTasksList'
        ));
    }
}
