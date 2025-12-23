<?php

use App\Models\Page;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

if (!function_exists('canAccess')) {

    /**
     * Check if the currently logged-in user can perform a specific action on a page.
     *
     * @param string $pageName   // Example: 'Users', 'Appointments'
     * @param string $actionName // Example: 'view', 'create', 'edit', 'delete'
     * @return bool
     */
    function canAccess($pageName, $actionName)
    {
        $user = auth()->user();

        // No logged-in user -> deny
        if (!$user) {
            return false;
        }

        // User must have a role assigned
        if (!$user->role_id) {
            return false;
        }

        // Find the page record
        $page = Page::where('name', $pageName)->first();
        if (!$page) {
            return false;
        }

        // Build the full permission name as stored in DB
        $fullPermissionName = strtolower(trim("{$pageName}.{$actionName}"));

        // Find the permission record
        $permission = Permission::where('name', $fullPermissionName)->first();
        if (!$permission) {
            return false;
        }

        // Check if mapping exists in role_page_permission pivot table
        $hasAccess = DB::table('role_page_permission')
            ->where('role_id', $user->role_id)
            ->where('page_id', $page->id)
            ->where('permission_id', $permission->id)
            ->exists();

        return $hasAccess;
    }
}
