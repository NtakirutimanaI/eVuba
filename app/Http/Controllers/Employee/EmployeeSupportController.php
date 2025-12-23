<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;

class EmployeeSupportController extends Controller
{
    /**
     * Display a listing of tickets assigned to the logged-in employee.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employeeId = Auth::id();

        $tickets = Ticket::where('assigned_to', $employeeId)
            ->with(['customer', 'category'])
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('employee.support.index', compact('tickets'));
    }

    /**
     * Return ticket details in JSON format.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($id)
    {
        $ticket = Ticket::with('customer')->findOrFail($id);

        return response()->json([
            'id' => $ticket->id,
            'customer' => $ticket->customer?->name ?? 'Guest Client',
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'status' => $ticket->status,
        ]);
    }

    /**
     * Store a reply message for a ticket.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'message' => 'required|string|max:2000',
        ]);

        TicketReply::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_staff_reply' => true,
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Return ticket reply history in JSON format.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function history($id)
    {
        $history = TicketReply::where('ticket_id', $id)
            ->with('user')
            ->orderBy('id', 'ASC')
            ->get()
            ->map(function ($item) {
                return [
                    'user_name' => $item->user->name ?? 'System',
                    'message' => $item->message,
                    'is_staff' => (bool)$item->is_staff_reply,
                    'date' => $item->created_at->format('M d, H:i'),
                ];
            });

        return response()->json($history);
    }
}
