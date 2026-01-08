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
    public function markAsRead(Request $request, $id)
    {
        Auth::user()->notifications()->where('id',$id)->update(['read_at'=>now()]);
        
        if($request->ajax() || $request->wantsJson()){
            return response()->json(['success'=>true, 'message'=>'Marked as read']);
        }
        return redirect()->route('notifications.page');
    }

    // Mark all notifications as read
    public function markAllRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        if($request->ajax() || $request->wantsJson()){
             return response()->json(['success'=>true, 'message'=>'All notifications marked as read.']);
        }
        return back()->with('success', 'All notifications marked as read.');
    }

    // Dismiss an announcement
    public function dismissAnnouncement(Request $request, $id)
    {
        $user = Auth::user();
        $announcement = Announcement::findOrFail($id);
        
        // Attach user to announcement if not already attached
        $user->announcements()->syncWithoutDetaching([$id]);
        
        if($request->ajax() || $request->wantsJson()){
             return response()->json(['success'=>true, 'message'=>'Announcement dismissed.']);
        }
        return back()->with('success', 'Announcement dismissed.');
    }

    // Delete a single notification
    public function delete(Request $request, $id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        
        if($request->ajax() || $request->wantsJson()){
             return response()->json(['success'=>true, 'message'=>'Notification deleted.']);
        }
        return back()->with('success', 'Notification deleted.');
    }

    // Clear all notifications (delete them)
    public function clearAll(Request $request)
    {
        Auth::user()->notifications()->delete();
        
        if($request->ajax() || $request->wantsJson()){
             return response()->json(['success'=>true, 'message'=>'All notifications cleared.']);
        }
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
