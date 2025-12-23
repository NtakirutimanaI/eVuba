<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Fetch all settings
        $allSettings = \App\Models\Setting::all();
        
        // Group them by 'group' column: ['general' => Collection, 'business' => Collection, ...]
        $settings = $allSettings->groupBy('group');

        // Helper to get value safely
        $get = function($key) use ($allSettings) {
            $s = $allSettings->firstWhere('key', $key);
            return $s ? $s->value : '';
        };

        return view('admin.settings', compact('settings', 'get'));
    }

    /**
     * Update settings.
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token']);

        foreach ($input as $key => $value) {
            // If value is array (for checkboxes not checked etc), handle it appropriately if needed
            // Here we assume simple key-value pairs
            \App\Models\Setting::set($key, $value, $request->get('group_'.$key, 'general'));
        }

        // Handle unchecked checkboxes (they don't send data)
        // You might need a hidden field approach or explicit list of boolean keys
        // For this demo, let's assume we handle booleans explicitly if needed, 
        // or just use 1/0 strings.
        
        return back()->with('success', 'Settings updated successfully.');
    }
    
    // Keep existing methods for permissions if needed, 
    // but for this task we focused on app settings.
    // If you need to keep legacy 'updatePermissions', leave it here.
    
    /**
     * Update permissions for a role (Legacy/Existing).
     */
    public function updatePermissions(Request $request)
    {
        $role = Role::findById($request->role_id);
        $permissions = $request->input('permissions', []);
        $allPermissions = [];
        foreach ($permissions as $page => $actions) {
            foreach ($actions as $action) {
                $allPermissions[] = $action . '-' . $page;
            }
        }
        $role->syncPermissions($allPermissions);
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }
}
