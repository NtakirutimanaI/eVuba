<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'stockIns', 'stockOuts'])->orderBy('id', 'DESC')->paginate(20); // Increase per page slightly for dense view
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products','categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name'=>['required','max:255', Rule::unique('categories')],
            'description'=>'nullable|string'
        ]);

        $category = Category::create([
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        return redirect()->route('admin.product.index')
            ->with('success','Category created successfully!')
            ->with('new_category',$category->id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>['required','max:255', Rule::unique('products')],
            'description'=>'nullable|string',
            'category_id'=>'required|exists:categories,id'
        ]);

        Product::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'category_id'=>$request->category_id
        ]);

        return redirect()->back()->with('success','Product created successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name'=>['required','max:255', Rule::unique('products')->ignore($product->id)],
            'description'=>'nullable|string',
            'category_id'=>'required|exists:categories,id'
        ]);

        $product->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'category_id'=>$request->category_id
        ]);

        return redirect()->back()->with('success','Product updated successfully!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success','Product deleted successfully!');
    }

    public function exportPdf(Request $request)
    {
        $query = Product::with(['category', 'stockIns', 'stockOuts'])->orderBy('id', 'DESC');

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $products = $query->get();
        $data = [
            'products' => $products,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        $pdf = Pdf::loadView('admin.products.pdf', $data);
        return $pdf->download('products_report.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
