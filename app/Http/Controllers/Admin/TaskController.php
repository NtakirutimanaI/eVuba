<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        return view('admin.task.index');
    }
}
