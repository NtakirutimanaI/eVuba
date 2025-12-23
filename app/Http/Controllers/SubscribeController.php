<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Mail\CompanyUpdateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Exports\SubscribersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SubscribeController extends Controller
{
    /**
     * Display all subscribers with optional search
     */
    public function index(Request $request)
    {
        $query = Subscriber::orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('email', 'like', "%{$search}%");
        }

        $subscribers = $query->get();
        return view('admin.subscribers.index', compact('subscribers'));
    }

    /**
     * Export subscribers as PDF
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Subscriber::query();
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        $subscribers = $query->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.subscribers.report_pdf', compact('subscribers'));
        return $pdf->download('Subscribers_Report_'.now()->format('Y-m-d').'.pdf');
    }

    /**
     * Export subscribers as Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        return Excel::download(new SubscribersExport($startDate, $endDate), 'Subscribers_'.now()->format('Y-m-d').'.xlsx');
    }

    /**
     * Store a new subscriber
     * Handles both normal form and AJAX submission
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Create subscriber if not exists
        $subscriber = Subscriber::firstOrCreate([
            'email' => $request->email
        ]);

        // Return response
        if ($request->ajax()) {
            return response()->json([
                'success' => 'Thank you for subscribing!',
                'subscriber' => $subscriber
            ]);
        }

        return back()->with('success', 'Thank you for subscribing!');
    }

    /**
     * Send company update to selected subscribers or all
     */
    public function sendCompanyUpdate(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'subscribers' => 'nullable|array',
        ]);

        $message = $request->message;

        // Determine recipients
        $subscribers = $request->subscribers
            ? Subscriber::whereIn('id', $request->subscribers)->get()
            : Subscriber::all();

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['subscribers' => 'No subscribers found to send update.']);
        }

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(new CompanyUpdateMail($message));
        }

        return back()->with('success', 'Company update queued and will be sent shortly.');
    }

    /**
     * Delete multiple subscribers
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:subscribers,id'
        ]);

        Subscriber::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => 'Selected subscribers deleted successfully.']);
    }

    /**
     * Delete a subscriber
     */
    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return back()->with('success', 'Subscriber deleted successfully.');
    }
}
