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

        // Get all published products with relations for stock calc
        $products = Product::with(['category', 'stockIns', 'stockOuts'])
            ->where('status', 'published')
            ->get();

        // Append remaining_stock for JS availability
        $products->each(function ($product) {
            $product->append('remaining_stock');
        });

        return view('customer.orders.index', compact('orders', 'products'));
    }

    /**
     * Store a new order submitted via modal/form
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Create new order with pending payment status
        $order = Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $request->quantity,
            'price' => $product->unit_price ?? 0,
            'status' => 'pending',
            'payment_method' => null, // Will be set after payment
            'payment_status' => 'pending',
            'transaction_ref' => null, // Will be set when payment is initiated
        ]);

        // Notify user
        $message = "Your order for {$product->name} (x{$request->quantity}) has been placed. Please complete payment to confirm.";

        auth()->user()->notify(new \App\Notifications\SystemAlert([
            'title' => 'Order Created',
            'message' => $message,
            'icon' => 'fa-shopping-cart',
            'action_url' => route('customer.orders.index')
        ]));

        // Return order ID so frontend can initiate payment
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully! Redirecting to payment...',
            'order_id' => $order->id,
            'redirect_to_payment' => true
        ]);
    }

    /**
     * Show details for a single product (for modal/view)
     */
    public function show($id)
    {
        $product = Product::with(['category', 'stockIns', 'stockOuts'])->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'image' => $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png'),
            'category' => $product->category->name ?? 'N/A',
            'price' => $product->unit_price ?? 0,
            'stock' => $product->remaining_stock ?? 0,
            'description' => $product->description ?? '',
        ]);
    }

    /**
     * Delete an order (Archive)
     */
    public function destroy($id)
    {
        try {
            $order = Order::where('user_id', auth()->id())->findOrFail($id);

            // Removed status restriction to allow deleting any order from archive
            // Soft delete or standard delete depending on model config
            $order->delete();

            return response()->json(['success' => true, 'message' => 'Order removed from archive successfully.']);
        } catch (\Exception $e) {
            \Log::error('Order deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete order. It may be linked to payment records.'
            ], 500);
        }
    }

    /**
     * Download Invoice PDF
     */
    public function downloadInvoice($id)
    {
        $order = Order::with(['user', 'product'])->where('user_id', auth()->id())->findOrFail($id);

        // Allow downloading invoice for any status (Pending, Paid, etc.)
        // if (!in_array($order->payment_status, ['approved', 'paid'])) {
        //     return redirect()->back()->with('error', 'Invoice is only available for paid orders.');
        // }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.documents.invoice_pdf', compact('order'));
        return $pdf->download('Invoice_' . $order->transaction_ref . '.pdf');
    }
}
