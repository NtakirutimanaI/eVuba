<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerSupportController extends Controller
{
    /**
     * Display all tickets for the manager with intelligence stats
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'category', 'replies'])->latest();

        // Search Intelligence
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('ticket_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status Filtering
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(15)->withQueryString();

        // Performance Metrics
        $stats = [
            'total'       => Ticket::count(),
            'open'        => Ticket::where('status', 'open')->count(),
            'processing'  => Ticket::where('status', 'in-progress')->count(),
            'resolved'    => Ticket::where('status', 'closed')->count(),
        ];

        return view('manager.support.index', compact('tickets', 'stats'));
    }

    /**
     * Send a reply to a ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $ticket = Ticket::findOrFail($id);

        $reply = new TicketReply();
        $reply->ticket_id = $ticket->id;
        $reply->is_staff_reply = true;
        $reply->user_id        = Auth::id();
        $reply->message   = $request->message;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('replies', 'public');
            $reply->attachment = $path;
        }

        $reply->save();

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Reply sent successfully',
                'reply'      => [
                    'is_staff_reply' => $reply->is_staff_reply,
                    'message'        => $reply->message,
                    'attachment'     => $reply->attachment ? asset('storage/'.$reply->attachment) : null,
                    'created_at'     => $reply->created_at->format('M d, Y h:i A'),
                ]
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Change ticket status and log the change
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in-progress,closed'
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;

        $ticket->update(['status' => $request->status]);

        TicketLog::create([
            'ticket_id'  => $ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'changed_by' => Auth::user()->name ?? 'Manager'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status'  => $ticket->status,
                'message' => 'Status updated successfully'
            ]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * AJAX: Fetch single ticket details
     */
    public function ajaxTicket($id)
    {
        $ticket = Ticket::with(['customer', 'category', 'replies'])->findOrFail($id);
        
        return response()->json([
            'id'          => $ticket->id,
            'ticket_no'   => $ticket->ticket_no,
            'subject'     => $ticket->subject,
            'description' => $ticket->description,
            'status'      => $ticket->status,
            'priority'    => $ticket->priority ?? 'normal',
            'customer'    => $ticket->customer,
            'category'    => $ticket->category,
            'attachment'  => $ticket->attachment,
            'created_at'  => $ticket->created_at->format('M d, Y'),
            'replies'     => $ticket->replies->map(function($r) {
                return [
                    'is_staff_reply' => $r->is_staff_reply,
                    'message'    => $r->message,
                    'attachment' => $r->attachment ? asset('storage/'.$r->attachment) : null,
                    'created_at' => $r->created_at->format('M d, Y h:i A'),
                ];
            })
        ]);
    }

    /**
     * AJAX: Fetch ticket logs
     */
    public function ajaxLogs($id)
    {
        $logs = TicketLog::where('ticket_id', $id)->latest()->get();
        return response()->json($logs);
    }
}
