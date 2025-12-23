<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('admin.schedule');
    }
}
