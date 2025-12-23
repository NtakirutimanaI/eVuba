<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerProductController extends Controller
{
    /**
     * Display all orders for the logged-in customer
     * and list all published products.
     */
    public function index()
    {
        $customerId = auth()->id(); // Logged-in user ID

        // Get customer's orders
        $orders = Order::where('user_id', $customerId)->get();

        // Get all published products
        $products = Product::where('status', 'published')->get();

        return view('customer.orders.index', compact('orders', 'products'));
    }

    /**
     * Store a new order submitted via modal/form
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        Order::create([
            'user_id'      => auth()->id(),
            'product_name' => $product->name,
            'quantity'     => $request->quantity,
            'price'        => $product->selling_price ?? 0,
            'status'       => 'pending',
        ]);

        return redirect()->route('customer.orders.index')
                         ->with('success', 'Order placed successfully!');
    }

    /**
     * Show details for a single product (for modal/view)
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Return JSON for modal (optional) or a Blade view if needed
        return response()->json([
            'id'       => $product->id,
            'name'     => $product->name,
            'image'    => $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png'),
            'category' => $product->category->name ?? 'N/A',
            'price'    => $product->selling_price ?? 0,
            'stock'    => $product->stock_quantity ?? 'N/A',
            'description' => $product->description ?? '',
        ]);
    }
}
