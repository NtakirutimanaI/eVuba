<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch all published products
        $products = Product::where('status', 'Published')
            ->orderBy('id', 'DESC')
            ->get();

        // Fetch published services
        $services = Service::where('is_published', 1)
            ->orderBy('id', 'asc')
            ->get();

        return view('web.index', compact('products', 'services'));
    }
}
