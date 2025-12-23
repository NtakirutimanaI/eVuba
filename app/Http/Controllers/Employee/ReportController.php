<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $reports = \App\Models\Report::where('user_id', $userId)->latest()->paginate(10);
        return view('employee.reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        \App\Models\Report::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully.');
    }
    public function getDetails($id)
    {
        $report = \App\Models\Report::findOrFail($id);

        if ($report->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'status' => $report->status,
                'created_at' => $report->created_at?->format('M d, Y h:i A') ?? 'N/A'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $report = \App\Models\Report::findOrFail($id);

        if ($report->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $report->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Report updated successfully.']);
        }

        return redirect()->back()->with('success', 'Report updated successfully.');
    }

    public function destroy($id)
    {
        $report = \App\Models\Report::findOrFail($id);

        if ($report->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $report->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Report deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Report deleted successfully.');
    }
}
