<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsOrdersExport; // We'll create this

class AdminOrderController extends Controller
{
    /**
     * Display all orders and products for the admin page
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5); // Default to 5
        if (!in_array($perPage, [5, 10, 15])) {
            $perPage = 5;
        }

        // Fetch accurate stats for all orders (not just the current page)
        $totalStats = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum(DB::raw('quantity * price')),
            'pending_count' => Order::where('status', 'pending')->count(),
            'total_orders'  => Order::count(),
        ];

        // Fetch all orders with related user (customer) and product
        $orders = Order::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Fetch all products for the left section (display small cards)
        $products = Product::orderBy('created_at', 'desc')->get();

        // Fetch categories for product creation
        $categories = Category::all();

        return view('admin.orders.index', compact('orders', 'products', 'categories', 'totalStats'));
    }

    /**
     * Approve / Update order status safely
     */
    public function update(Request $request, $id)
    {
        // Validate status input
        $request->validate([
            'status' => 'required|in:pending,processing,approved,completed,cancelled'
        ]);

        // Find order
        $order = Order::findOrFail($id);

        // Update status as string to prevent data truncation
        $order->status = (string) $request->status;
        $order->save();

        // Notify Customer
        if ($order->customer && $order->customer->user) {
            $order->customer->user->notify(new \App\Notifications\SystemAlert([
                'title' => 'Order Status Updated',
                'message' => 'Your order #'.$order->order_no.' is now '.ucfirst($order->status).'.',
                'icon' => 'fa-shopping-bag',
                'action_url' => route('customer.orders.index')
            ]));
        }

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Delete an order
     */
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Order deleted successfully!');
    }

    /**
     * Store new product (Create or Publish)
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_code' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'action' => 'required|in:create,publish'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $status = $request->action === 'publish' ? 'published' : 'unpublished';

        Product::create([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'product_code' => $request->product_code ?? null,
            'category_id' => $request->category_id ?? null,
            'image' => $imagePath,
            'status' => $status
        ]);

        return redirect()->back()->with('success', 'Product '.($status === 'published' ? 'published' : 'created').' successfully!');
    }

    /**
     * Publish / Unpublish a product
     */
    public function toggleProductStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status === 'published' ? 'unpublished' : 'published';
        $product->save();

        return redirect()->back()->with('success', 'Product status updated!');
    }

    /**
     * Update product details (including image)
     */
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_code' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description ?? null;
        $product->product_code = $request->product_code ?? null;
        $product->category_id = $request->category_id ?? null;

        if ($request->hasFile('image')) {
            // Delete old image safely
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Delete a product
     */
    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
    // Show the report page
public function generateReport()
{
    $products = Product::with('category')->orderBy('created_at','desc')->get();
    $orders = Order::with('customer')->orderBy('created_at','desc')->get();

    return view('admin.orders.report', compact('products', 'orders'));
}

// Generate PDF
public function generateReportPDF(Request $request)
{
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    $products = Product::with('category')->orderBy('created_at','desc')->get();
    
    $query = Order::with('customer')->orderBy('created_at','desc');
    if ($startDate) $query->whereDate('created_at', '>=', $startDate);
    if ($endDate) $query->whereDate('created_at', '<=', $endDate);
    $orders = $query->get();

    $pdf = Pdf::loadView('admin.orders.report_pdf', compact('products', 'orders'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('eVubaConnect_Report_'.now()->format('Y-m-d_H:i').'.pdf');
}

// Generate Excel
public function generateReportExcel(Request $request)
{
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');
    
    return Excel::download(new ProductsOrdersExport($startDate, $endDate), 'eVubaConnect_Report_'.now()->format('Y-m-d_H:i').'.xlsx');
}

}
