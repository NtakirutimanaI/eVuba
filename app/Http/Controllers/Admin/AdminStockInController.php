<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockIn;
use Illuminate\Validation\Rule;

// PDF & Excel
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockInExport;

// Email Notifications
use App\Mail\StockNotificationMail;
use Illuminate\Support\Facades\Mail;

class AdminStockInController extends Controller
{
    /**
     * Display stock in page with products, suppliers, and existing stock ins
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $query = StockIn::with(['product','supplier','user'])->orderBy('id','DESC');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('stock_in_date', [$request->start_date, $request->end_date]);
        }

        $stockIns = StockIn::with(['product','supplier'])
            ->orderBy('stock_in_date', 'desc')
            ->paginate(15);
            
        $currency = \App\Models\Setting::get('currency', 'FRW');

        return view('admin.stock_in.index', compact('products','suppliers','stockIns', 'currency'));
    }

    /**
     * Store new stock in
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'stock_in_date' => 'required|date',
            'type' => ['required', Rule::in(['purchase','return','production','donation'])],
            'note' => 'nullable|string',
        ]);

        $exists = StockIn::where('product_id', $request->product_id)
            ->where('stock_in_date', $request->stock_in_date)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['duplicate' => 'A stock entry for this product on this date already exists.']);
        }

        $stock = StockIn::create([
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'total_cost' => $request->quantity * $request->unit_cost,
            'stock_in_date' => $request->stock_in_date,
            'type' => $request->type,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);

        $threshold = 50; 
        $notifyEmails = ['manager@example.com']; 

        if ($stock->quantity >= $threshold || $stock->type === 'donation') {
            try {
                Mail::to($notifyEmails)->send(new StockNotificationMail($stock));
            } catch (\Exception $e) {}
        }

        return redirect()->back()->with('success','Stock added successfully!');
    }

    public function update(Request $request, $id)
    {
        $stock = StockIn::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'stock_in_date' => 'required|date',
            'type' => ['required', Rule::in(['purchase','return','production','donation'])],
            'note' => 'nullable|string',
        ]);

        $exists = StockIn::where('product_id', $stock->product_id)
            ->where('stock_in_date', $request->stock_in_date)
            ->where('id','!=',$id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['duplicate' => 'Another stock entry for this product on this date already exists.']);
        }

        $stock->update([
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'total_cost' => $request->quantity * $request->unit_cost,
            'stock_in_date' => $request->stock_in_date,
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success','Stock updated successfully!');
    }

    public function destroy($id)
    {
        $stock = StockIn::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success','Stock deleted successfully!');
    }

    public function generatePdf(Request $request)
    {
        $query = StockIn::with(['product','supplier'])->orderBy('id','DESC');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('stock_in_date', [$request->start_date, $request->end_date]);
        }

        $stockIns = $query->get();
        $start = $request->start_date ?? null;
        $end = $request->end_date ?? null;

        $pdf = Pdf::loadView('admin.stock_in.report_pdf', compact('stockIns','start','end'));
        return $pdf->download('stock_in_report.pdf');
    }

    public function generateExcel(Request $request)
    {
        $start = $request->start_date ?? null;
        $end = $request->end_date ?? null;

        return Excel::download(new StockInExport($start, $end), 'stock_in_report.xlsx');
    }

    // ---------------- SUPPLIER METHODS ----------------

    /**
     * Show add supplier form
     */
    public function addSupplier()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store new supplier
     */
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Supplier::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.stock_in.addSupplier')
                         ->with('success', 'Supplier added successfully!');
    }
}
