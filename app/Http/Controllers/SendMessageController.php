<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageUs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MessageReplyMail;

class SendMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = MessageUs::orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            if ($request->status == 'unread') $query->where('read', false);
            if ($request->status == 'read') $query->where('read', true);
        }

        $messages = $query->withCount('replies')->paginate(15)->withQueryString();
        
        $stats = [
            'total'  => MessageUs::count(),
            'unread' => MessageUs::where('read', false)->count(),
            'read'   => MessageUs::where('read', true)->count(),
        ];

        return view('admin.messages.index', compact('messages', 'stats'));
    }

    /**
     * Mark all unread messages as read
     */
    public function markAllRead()
    {
        MessageUs::where('read', false)->update(['read' => true]);
        return back()->with('success', 'All messages have been marked as read.');
    }

    /**
     * Fetch message details via AJAX
     */
    public function ajaxDetails($id)
    {
        $message = MessageUs::with('replies.user')->findOrFail($id);
        if (!$message->read) {
            $message->read = true;
            $message->save();
        }
        return response()->json($message);
    }

    /**
     * Store a reply to a message
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply_content' => 'required|string',
        ]);

        $message = MessageUs::findOrFail($id);
        
        $reply = \App\Models\MessageReply::create([
            'message_us_id' => $message->id,
            'user_id'       => auth()->id(),
            'reply_content' => $request->reply_content,
        ]);

        // Send email
        try {
            Mail::to($message->email)->send(new MessageReplyMail($reply));
        } catch (\Exception $e) {
            Log::error("Mail error: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully!',
            'reply'   => [
                'content'    => $reply->reply_content,
                'user_name'  => auth()->user()->name,
                'created_at' => $reply->created_at->diffForHumans()
            ]
        ]);
    }

    /**
     * Show a single message (Admin view)
     */
    public function show($id)
    {
        $message = MessageUs::findOrFail($id);
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Store a new message from front-end contact form
     */
    public function send(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'message'    => 'required|string',
        ]);

        $messageUs = MessageUs::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'message'    => $request->message,
        ]);

        // Notify Admins
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SystemAlert([
                'title' => 'New Contact Message',
                'message' => 'New inquiry from '.$request->first_name.' '.$request->last_name.'.',
                'icon' => 'fa-envelope',
                'action_url' => route('admin.messages.index')
            ]));
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Message sent successfully!']);
        }

        return back()->with('success', 'Message sent successfully!');
    }

    /**
     * Remove the specified message (Admin action)
     */
    public function destroy($id)
    {
        $message = MessageUs::findOrFail($id);
        $message->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Message deleted successfully.']);
        }

        return redirect()->route('admin.messages.index')
                         ->with('success', 'Message deleted successfully.');
    }

    /**
     * Toggle read/unread status
     */
    public function markRead($id)
    {
        $message = MessageUs::findOrFail($id);
        $message->read = !$message->read;
        $message->save();

        return response()->json([
            'success' => true,
            'message' => $message->read ? 'Message marked as read.' : 'Message marked as unread.',
            'read' => $message->read
        ]);
    }
}
