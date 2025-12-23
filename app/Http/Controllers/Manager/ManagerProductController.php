<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ManagerProductController extends Controller
{
    /**
     * Display list of products and categories.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $products = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Inventory Intelligence
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'low_stock' => Product::get()->filter(function($p) { return $p->remaining_stock < 10; })->count(),
            'out_of_stock' => Product::get()->filter(function($p) { return $p->remaining_stock <= 0; })->count(),
        ];

        return view('manager.products.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Store a new category from popup.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        if (Category::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Category already exists!');
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('manager.product.index')
            ->with('success', 'Category created successfully!')
            ->with('new_category', $category->id);
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if (Product::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Product already exists!');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    /**
     * Update a product.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);

        if (Product::where('name', $request->name)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with('error', 'Another product with this name already exists!');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Delete a product.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    /**
     * Export products to Excel.
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new ProductsExport($request->start_date, $request->end_date), 'products.xlsx');
    }

    /**
     * Export products to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $products = $query->orderBy('id', 'DESC')->get();
        $pdf = Pdf::loadView('manager.products.pdf', compact('products'));
        return $pdf->download('products_catalog.pdf');
    }
}
