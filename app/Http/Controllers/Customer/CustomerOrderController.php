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
            'payment_method' => 'nullable|string',
            'transaction_ref' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Logic for payment status: SIMULATED AUTO-APPROVAL
        // If payment method is provided, we simulate a successful immediate payment.
        $isPaid = !empty($request->payment_method);
        $paymentStatus = $isPaid ? 'approved' : 'pending';
        $orderStatus = $isPaid ? 'processing' : 'pending';

        // Generate mock transaction ref if needed
        $txRef = $request->transaction_ref;
        if ($isPaid && empty($txRef)) {
            $txRef = 'SIM-' . strtoupper(uniqid());
        }

        // Create new order
        Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $request->quantity,
            'price' => $product->unit_price ?? 0,
            'status' => $orderStatus,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
            'transaction_ref' => $txRef,
        ]);

        // Notify user
        $message = "Your order for {$product->name} (x{$request->quantity}) has been placed.";
        if ($isPaid) {
            $message .= " Payment confirmed via {$request->payment_method}.";
        } else {
            $message .= " Status: Pending Payment.";
        }

        auth()->user()->notify(new \App\Notifications\SystemAlert([
            'title' => $isPaid ? 'Order Paid & Processing' : 'Order Received',
            'message' => $message,
            'icon' => $isPaid ? 'fa-check-circle' : 'fa-hourglass-start',
            'action_url' => route('customer.orders.index')
        ]));

        return response()->json(['success' => true, 'message' => 'Order placed successfully!']);
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
     * Delete a pending order
     */
    public function destroy($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be cancelled.'], 403);
        }

        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order cancelled successfully.']);
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
