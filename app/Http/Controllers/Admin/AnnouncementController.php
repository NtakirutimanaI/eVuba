<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_role' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'target_role' => $request->target_role,
            'is_active' => $request->has('is_active') ? $request->is_active : true
        ]);

        return back()->with('success', 'Announcement published successfully.');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_role' => 'nullable|string'
        ]);

        $announcement->update([
            'title' => $request->title,
            'message' => $request->message,
            'target_role' => $request->target_role,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Announcement updated successfully.');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    public function toggleStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        return back()->with('success', 'Announcement status updated.');
    }
}
