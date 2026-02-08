<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminEmployeeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::with('user')->latest()->paginate(10);
        return view('admin.employee_reports.index', compact('reports'));
    }

    /**
     * Get details of a specific report (AJAX).
     */
    public function details($id)
    {
        $report = Report::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'status' => $report->status,
                'employee' => $report->user ? $report->user->name : 'Unknown',
                'created_at' => $report->created_at->format('M d, Y h:i A')
            ]
        ]);
    }

    /**
     * Update the status of the report.
     */
    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,reviewed,approved,rejected',
        ]);

        $report->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Report status updated.']);
        }

        return redirect()->back()->with('success', 'Report status updated.');
    }
}
