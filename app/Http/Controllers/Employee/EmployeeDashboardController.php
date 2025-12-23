<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Make sure the User model has these relationships defined:
        // tasks(), reports(), appointments()
        $tasksCount = $user->tasks()->count() ?? 0;
        $reportsCount = $user->reports()->count() ?? 0;
        $appointmentsCount = $user->assignedAppointments()->count() ?? 0;

        // Optionally, recent items
        $recentTasks = $user->tasks()->latest()->take(3)->get();
        $recentReports = $user->reports()->latest()->take(3)->get();
        $recentAppointments = $user->assignedAppointments()->latest()->take(3)->get();

        return view('employee.dashboard', compact(
            'tasksCount',
            'reportsCount',
            'appointmentsCount',
            'recentTasks',
            'recentReports',
            'recentAppointments'
        ));
    }
}
