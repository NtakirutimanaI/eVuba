<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManagerOrderController extends Controller
{
    /**
     * Display all orders and products for the manager page
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'product']);

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Search Intelligence
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($cq) use ($search) {
                    $cq->where('name', 'LIKE', "%{$search}%");
                })->orWhere('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $products = Product::orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('name')->get();

        // Operational Intelligence
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum(\DB::raw('quantity * price')),
        ];

        // Order Pulse (Latest 7 days)
        $chartData = Order::where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(quantity * price) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        return view('manager.orders.index', compact('orders', 'products', 'categories', 'stats', 'chartData'));
    }

    /**
     * Approve / Update order status safely
     */
    public function updateStatus(Request $request, $id)
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

    /**
     * Export Orders to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Order::with(['customer', 'product']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('manager.orders.report_pdf', compact('orders'));
        return $pdf->download('orders_report.pdf');
    }

    /**
     * Export Orders to Excel
     */
    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OrderExport($request->start_date, $request->end_date), 'orders_report.xlsx');
    }
}
