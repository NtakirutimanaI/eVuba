<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    /**
     * Display all orders for the logged-in customer
     * and list all published products.
     */
    public function index()
    {
        $customerId = auth()->id();

        // Get customer's orders with product relationship
        $orders = Order::with('product')
            ->where('user_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();

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

        // Create new order using only product_id
        Order::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity'   => $request->quantity,
            'price'      => $product->selling_price ?? 0,
            'status'     => 'pending',
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

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'image'       => $product->image ? asset('storage/products/'.$product->image) : asset('images/no-image.png'),
            'category'    => $product->category->name ?? 'N/A',
            'price'       => $product->selling_price ?? 0,
            'stock'       => $product->stock_quantity ?? 'N/A',
            'description' => $product->description ?? '',
        ]);
    }
}
