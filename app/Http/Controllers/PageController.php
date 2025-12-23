<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Get all pages accessible for a specific role
     */
    public function getAccessiblePagesForRole($roleId)
    {
        $pages = DB::table('role_page_permission')
            ->join('pages', 'pages.id', '=', 'role_page_permission.page_id')
            ->join('permissions', 'permissions.id', '=', 'role_page_permission.permission_id')
            ->where('role_page_permission.role_id', $roleId)
            ->select('pages.id', 'pages.name')
            ->distinct()
            ->get();

        return $pages;
    }
}
