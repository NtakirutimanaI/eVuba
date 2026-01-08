<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportCategory;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerSupportController extends Controller
{
    /**
     * Show support page with categories and tickets
     */
    public function index()
    {
        $categories = SupportCategory::all();

        // Load tickets for the logged-in customer
        $tickets = Ticket::where('customer_id', Auth::id())
            ->with(['category', 'replies', 'logs'])
            ->orderBy('id', 'DESC')
            ->get();

        return view('customer.support.index', compact('categories', 'tickets'));
    }

    /**
     * Store a new ticket with optional attachment
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_no' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'category_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
        ]);

        // Handle file upload
        $fileName = null;
        if ($request->hasFile('attachment')) {
            $fileName = time() . '_' . uniqid() . '.' . $request->attachment->extension();
            $request->attachment->move(public_path('tickets'), $fileName);
        }

        Ticket::create([
            'ticket_no' => $request->ticket_no,
            'customer_id' => Auth::id(),
            'category_id' => $request->category_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment' => $fileName,
            'status' => 'open',
        ]);

        // Notify user
        Auth::user()->notify(new \App\Notifications\SystemAlert([
            'title' => 'Resolution Protocol Initialized',
            'message' => "Support request #{$request->ticket_no} has been logged via the operations center.",
            'icon' => 'fa-headset',
            'action_url' => route('customer.support.index')
        ]));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Your ticket has been submitted successfully.']);
        }
        return back()->with('success', 'Your ticket has been submitted successfully.');
    }

    /**
     * Edit ticket (load for editing)
     */
    public function edit($id)
    {
        $ticket = Ticket::where('customer_id', Auth::id())
            ->with('category')
            ->findOrFail($id);

        $categories = SupportCategory::all();

        return view('customer.support.edit', compact('ticket', 'categories'));
    }

    /**
     * Update ticket
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('customer_id', Auth::id())->findOrFail($id);

        $request->validate([
            'category_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($ticket->attachment && file_exists(public_path('tickets/' . $ticket->attachment))) {
                unlink(public_path('tickets/' . $ticket->attachment));
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->attachment->extension();
            $request->attachment->move(public_path('tickets'), $fileName);
            $ticket->attachment = $fileName;
        }

        $ticket->update([
            'category_id' => $request->category_id,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->route('customer.support.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Delete ticket
     */
    public function destroy($id)
    {
        $ticket = Ticket::where('customer_id', Auth::id())->findOrFail($id);

        if ($ticket->attachment && file_exists(public_path('tickets/' . $ticket->attachment))) {
            unlink(public_path('tickets/' . $ticket->attachment));
        }

        $ticket->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Ticket deleted successfully.']);
        }

        return back()->with('success', 'Ticket deleted successfully.');
    }

    /**
     * AJAX load ticket details for modal
     */
    public function ajaxTicket($id)
    {
        $ticket = Ticket::with(['category', 'replies', 'logs'])->findOrFail($id);

        return response()->json([
            'id' => $ticket->id,
            'ticket_no' => $ticket->ticket_no,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'status' => ucfirst($ticket->status),
            'attachment' => $ticket->attachment ? url('tickets/' . $ticket->attachment) : null,
            'category' => $ticket->category,
            'assigned_to' => $ticket->assignedUser ? $ticket->assignedUser->name : null,
            'replies' => $ticket->replies->map(function ($r) {
                return [
                    'id' => $r->id,
                    'message' => $r->message,
                    'is_staff_reply' => $r->is_staff_reply,
                    'attachment' => $r->attachment ? url('tickets/' . $r->attachment) : null,
                    'created_at' => $r->created_at,
                ];
            }),
            'logs' => $ticket->logs->map(function ($l) {
                return [
                    'id' => $l->id,
                    'old_status' => $l->old_status,
                    'new_status' => $l->new_status,
                    'changed_by' => $l->changed_by,
                    'created_at' => $l->created_at,
                ];
            }),
        ]);
    }

    /**
     * Customer reply (AJAX)
     */
    public function ajaxReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $ticket = Ticket::findOrFail($id);

        $fileName = null;
        if ($request->hasFile('attachment')) {
            $fileName = time() . '_reply_' . uniqid() . '.' . $request->attachment->extension();
            $request->attachment->move(public_path('tickets'), $fileName);
        }

        $reply = $ticket->replies()->create([
            'message' => $request->message,
            'is_staff_reply' => false,
            'user_id' => Auth::id(),
            'attachment' => $fileName,
        ]);

        return response()->json([
            'id' => $reply->id,
            'message' => $reply->message,
            'is_staff_reply' => $reply->is_staff_reply,
            'attachment' => $reply->attachment ? url('tickets/' . $reply->attachment) : null,
            'created_at' => $reply->created_at,
        ]);
    }
}
