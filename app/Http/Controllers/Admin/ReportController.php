<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\Ticket;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => Sale::sum('total_amount'),
            'total_orders' => Order::count(),
            'total_customers' => Customer::count(),
            'total_subscribers' => Subscriber::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'low_stock' => Product::all()->filter(function($p) {
                return $p->remaining_stock < 10;
            })->count(),
        ];

        return view('admin.report', compact('stats'));
    }
}
