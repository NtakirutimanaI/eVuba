<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;

class WebServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_published', 1)
            ->orderBy('id', 'asc')
            ->get();

        return view('web.services', compact('services'));
    }
}
