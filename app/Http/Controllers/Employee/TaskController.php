<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $tasks = \App\Models\Task::where('user_id', $userId)->latest()->paginate(10);
        return view('employee.tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $task = \App\Models\Task::findOrFail($id);
        
        if ($task->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Task status updated.']);
        }

        return redirect()->back()->with('success', 'Task status updated.');
    }
    public function getDetails($id)
    {
        $task = \App\Models\Task::findOrFail($id);

        if ($task->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'created_at' => $task->created_at->format('M d, Y')
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = \App\Models\Task::findOrFail($id);

        if ($task->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Task updated successfully.']);
        }

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroy($id)
    {
        $task = \App\Models\Task::findOrFail($id);

        if ($task->user_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $task->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
