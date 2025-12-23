<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ManagerServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        $services = Service::with('employee')->paginate(10);
        return view('manager.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $employees = User::where('role', 'employee')->get();
        return view('manager.services.create', compact('employees'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('manager.services.index')->with('success', 'Service created successfully!');
    }

    /**
     * Show the form for editing a service.
     */
    public function edit(Service $service)
    {
        $employees = User::where('role', 'employee')->get();
        return view('manager.services.edit', compact('service', 'employees'));
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service->name = $request->name;
        $service->description = $request->description;
        $service->employee_id = $request->employee_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image && Storage::exists('public/' . $service->image)) {
                Storage::delete('public/' . $service->image);
            }
            $service->image = $request->file('image')->store('services', 'public');
        }

        $service->save();

        return redirect()->route('manager.services.index')->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service)
    {
        // Delete image if exists
        if ($service->image && Storage::exists('public/' . $service->image)) {
            Storage::delete('public/' . $service->image);
        }

        $service->delete();

        return redirect()->route('manager.services.index')->with('success', 'Service deleted successfully!');
    }
     public function publish(Service $service)
    {
        // Toggle the is_published flag
        $service->is_published = !$service->is_published;
        $service->save();

        return redirect()->back()->with('success', 'Service status updated successfully.');
    }
}
