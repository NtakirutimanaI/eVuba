<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerInvoiceController extends Controller
{
    public function index()
    {
        // Fetch all orders that have an invoice (approved or paid)
        // Fetch all orders regardless of status so Pending invoices show up too
        $invoices = \App\Models\Order::where('user_id', auth()->id())
            // ->whereIn('payment_status', ['approved', 'paid']) // Removed to show pending invoices

            ->with(['product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.invoices.index', compact('invoices'));
    }
}
