<?php

namespace App\Http\Controllers\Manager;

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

class ManagerStockInController extends Controller
{
    /**
     * Display stock in page with products, suppliers, and existing stock ins
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        $query = StockIn::with(['product','supplier','user']);

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('stock_in_date', [$request->start_date, $request->end_date]);
        }

        // Stats Intelligence
        $stats = [
            'total_entries' => (clone $query)->count(),
            'total_quantity' => (clone $query)->sum('quantity'),
            'total_investment' => (clone $query)->sum('total_cost'),
            'this_month' => StockIn::whereMonth('stock_in_date', now()->month)->count(),
        ];

        $stockIns = $query->orderBy('id','DESC')->paginate(10)->withQueryString();

        // Chart Data (Latest 7 days)
        $chartData = StockIn::selectRaw('DATE(stock_in_date) as date, SUM(quantity) as qty')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        return view('manager.stock_in.index', compact('products','suppliers','stockIns', 'stats', 'chartData'));
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

        // Create stock entry
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

        // ---------------- ALERT LOGIC ----------------
        $threshold = 50; 
        $notifyEmails = ['manager@example.com']; 

        if ($stock->quantity >= $threshold || $stock->type === 'donation') {
            try {
                Mail::to($notifyEmails)->send(new StockNotificationMail($stock));
            } catch (\Exception $e) {
                // Prevent errors from breaking stock creation
            }
        }
        // ------------------------------------------------

        return redirect()->back()->with('success','Stock added successfully!');
    }

    /**
     * Update existing stock in
     */
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

        // Update stock
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

    /**
     * Delete stock in record
     */
    public function destroy($id)
    {
        $stock = StockIn::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success','Stock deleted successfully!');
    }

    /**
     * Export PDF report
     */
    public function exportPdf(Request $request)
    {
        $query = StockIn::with(['product','supplier']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('stock_in_date', [$request->start_date, $request->end_date]);
        }

        $stockIns = $query->orderBy('id','DESC')->get();

        $pdf = Pdf::loadView('manager.stock_in.report_pdf', compact('stockIns'));
        return $pdf->download('stock_in_report.pdf');
    }

    /**
     * Export Excel report
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new StockInExport($request->start_date, $request->end_date), 'stock_in_report.xlsx');
    }
}
