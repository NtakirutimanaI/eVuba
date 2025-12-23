<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Mail;

class UserReportController extends Controller
{
    // View report
    public function index(Request $request)
    {
        $users = $this->fetchUsers($request);
        return view('admin.users.report', [
            'users' => $users,
            'report_type' => $request->report_type ?? 'daily',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $users = $this->fetchUsers($request);
        $pdf = PDF::loadView('admin.users.report_pdf', compact('users'));
        return $pdf->download("users_report.pdf");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(new UsersExport($request), 'users_report.xlsx');
    }

    // Email report
    public function emailReport(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $users = $this->fetchUsers($request);
        $email = $request->email;

        Mail::send('admin.users.email-report', compact('users'), function ($message) use ($email) {
            $message->to($email)->subject('Users Report');
        });

        return back()->with('success', 'Report emailed successfully!');
    }

    // Helper to fetch users
    private function fetchUsers(Request $request)
    {
        $query = User::query();
        $type = $request->report_type ?? 'daily';
        $start = $request->start_date;
        $end = $request->end_date;

        switch ($type) {
            case 'daily':
                $query->whereDate('created_at', today());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'yearly':
                $query->whereYear('created_at', now()->year);
                break;
            case 'custom':
                if ($start && $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
                break;
        }

        return $query->paginate(10);
    }
}
