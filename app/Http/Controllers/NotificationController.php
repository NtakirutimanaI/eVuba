<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Message;

class NotificationController extends Controller
{
    // Notifications page
    public function notificationsPage()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->get();

        $announcements = Announcement::where('is_active',1)
            ->where(function($q) use ($user){
                $q->whereNull('target_role')->orWhere('target_role',$user->role);
            })
            ->whereDoesntHave('users', fn($q)=>$q->where('user_id',$user->id))
            ->latest()->get();

        return view('notifications', compact('notifications','announcements'));
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        Auth::user()->notifications()->where('id',$id)->update(['read_at'=>now()]);
        return redirect()->route('notifications.page');
    }

    // Mark all notifications as read
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    // Dismiss an announcement
    public function dismissAnnouncement($id)
    {
        $user = Auth::user();
        $announcement = Announcement::findOrFail($id);
        
        // Attach user to announcement if not already attached
        $user->announcements()->syncWithoutDetaching([$id]);
        
        return back()->with('success', 'Announcement dismissed.');
    }

    // Clear all notifications (delete them)
    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'All notifications cleared.');
    }

    // Messages page
    public function messagesPage()
    {
        $user = Auth::user();
        $messages = Message::where('recipient_id',$user->id)->latest()->get();

        return view('messages', compact('messages'));
    }

    // Mark message as read
    public function markMessageAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['read_at'=>now()]);
        return redirect()->route('messages.page');
    }
}
