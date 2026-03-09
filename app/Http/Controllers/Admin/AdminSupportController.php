<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketLog;
use App\Models\Customer;
use App\Models\SupportCategory;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf; // for PDF export
use Maatwebsite\Excel\Facades\Excel; // for Excel export
use App\Exports\SupportTicketsExport; // Excel export class (adjust if needed)

class AdminSupportController extends Controller
{
    /**
     * Display all tickets with required data
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'submitter', 'category', 'assignedUser'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(5)->withQueryString();

        // Compute stats using database aggregates
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'high_priority' => Ticket::where('priority', 'high')->count(),
            // Cross-role support control (e.g. tracking tickets handled by different user types if applicable)
            'replies_count' => TicketReply::count(),
        ];

        $customers = Customer::orderBy('name')->get();
        // Correcting category fetch to use SupportCategory
        $categories = SupportCategory::orderBy('name')->get();

        // Fetching agents — using plain role column (not Spatie roles)
        $agents = User::whereIn('role', ['admin', 'manager', 'employee'])
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('admin.support.index', compact('tickets', 'customers', 'categories', 'stats', 'agents'));
    }

    /**
     * Store new support category
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Using SupportCategory model instead of generic Category
        SupportCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Support Category created successfully.');
    }

    /**
     * Delete support category
     */
    public function destroyCategory($id)
    {
        SupportCategory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Support Category deleted successfully.');
    }

    /**
     * Update ticket status and log history
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;

        $ticket->status = $request->status;
        $ticket->save();

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'status_changed',
            'description' => 'Status changed from ' . $oldStatus . ' to ' . $ticket->status . ' by ' . Auth::user()->name,
        ]);

        // Notify Customer
        if ($ticket->customer && $ticket->customer->user) {
            $ticket->customer->user->notify(new \App\Notifications\SystemAlert([
                'title' => 'Ticket Status Updated',
                'message' => 'The status of your ticket #' . $ticket->ticket_no . ' has been changed to ' . ucfirst($ticket->status) . '.',
                'icon' => 'fa-clipboard-check',
                'action_url' => route('customer.support.index')
            ]));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Ticket status updated']);
        }

        return redirect()->back()->with('success', 'Ticket status updated.');
    }

    /**
     * AJAX — return full ticket details for modal
     */
    public function ajaxTicket($id)
    {
        $ticket = Ticket::with(['customer', 'submitter', 'category', 'assignedUser', 'replies'])
            ->findOrFail($id);

        $requesterName = $ticket->customer->name ?? $ticket->submitter->name ?? 'Guest User';
        $requesterEmail = $ticket->customer->email ?? $ticket->submitter->email ?? 'No email provided';

        return response()->json([
            'id' => $ticket->id,
            'ticket_no' => $ticket->ticket_no,
            'customer' => $ticket->customer,
            'requester_name' => $requesterName,
            'requester_email' => $requesterEmail,
            'category' => $ticket->category,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'priority' => $ticket->priority,
            'assigned' => $ticket->assignedUser,
            'attachment' => $ticket->attachment,
            'status' => $ticket->status,
            'replies' => $ticket->replies->map(function ($reply) {
                return [
                    'is_staff_reply' => $reply->is_staff_reply,
                    'message' => $reply->message,
                    'attachment' => $reply->attachment ? asset('storage/' . $reply->attachment) : null,
                    'created_at' => $reply->created_at,
                ];
            }),
        ]);
    }

    /**
     * AJAX — ticket logs/history
     */
    public function ajaxLogs($id)
    {
        $logs = TicketLog::where('ticket_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }

    /**
     * Post a reply to a ticket and handle attachment safely
     */
    public function reply(Request $request, $id)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:2000',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            ]);

            $ticket = Ticket::findOrFail($id);

            $reply = new TicketReply();
            $reply->ticket_id = $ticket->id;
            $reply->message = $request->message;
            $reply->user_id = Auth::id();
            $reply->is_staff_reply = true;

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('replies', 'public');
                $reply->attachment = $path;
            }

            $reply->save();

            // Notify Customer (if admin/manager/employee replied)
            if ($ticket->customer && $ticket->customer->user) {
                $ticket->customer->user->notify(new \App\Notifications\SystemAlert([
                    'title' => 'New Support Response',
                    'message' => 'A support agent has replied to your ticket #' . $ticket->ticket_no . '.',
                    'icon' => 'fa-reply',
                    'action_url' => route('customer.support.index')
                ]));
            }

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully',
                'attachment' => $reply->attachment ? asset('storage/' . $reply->attachment) : null,
            ]);
        } catch (Exception $e) {
            \Log::error('Reply Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Assign ticket to a user/staff and log history
     */
    public function assignTicket(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;

        $ticket->assigned_to = $request->assigned_to;
        $ticket->save();

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'assigned',
            'description' => 'Ticket assigned to ' . optional(User::find($request->assigned_to))->name . ' by ' . Auth::user()->name,
        ]);

        // Notify Assigned Agent
        try {
            $agent = User::find($request->assigned_to);
            if ($agent) {
                $agent->notify(new \App\Notifications\SystemAlert([
                    'title' => 'New Ticket Assigned',
                    'message' => 'You have been assigned to handle ticket #' . $ticket->ticket_no . '.',
                    'icon' => 'fa-user-tag',
                    'action_url' => route('admin.support.index')
                ]));
            }
        } catch (\Exception $e) {
            \Log::error('Notification Failed: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket assigned successfully',
                'assigned_to' => optional($ticket->assignedUser)->name,
            ]);
        }

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    /**
     * Display report page
     */
    public function report(Request $request)
    {
        $tickets = Ticket::with(['customer', 'category', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.support.report', compact('tickets'));
    }

    /**
     * Export tickets to PDF with filters
     */
    public function exportPdf(Request $request)
    {
        $query = Ticket::with(['assignedUser', 'category', 'customer']);

        // Filtering logic
        if ($request->filled('start_date'))
            $query->whereDate('created_at', '>=', $request->start_date);
        if ($request->filled('end_date'))
            $query->whereDate('created_at', '<=', $request->end_date);
        if ($request->filled('category_id'))
            $query->where('category_id', $request->category_id);

        if ($request->filled('role')) {
            $query->whereHas('assignedUser', function ($q) use ($request) {
                $q->role($request->role);
            });
        }

        $tickets = $query->get();
        $start = $request->start_date;
        $end = $request->end_date;

        $pdf = Pdf::loadView('admin.support.tickets_pdf', compact('tickets', 'start', 'end'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('support_report_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export tickets to Excel with filters
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new SupportTicketsExport($request->all()), 'support_report_' . now()->format('Ymd_His') . '.xlsx');
    }
}
