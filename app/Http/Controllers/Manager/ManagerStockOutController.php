<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockOut;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockOutExport;

class ManagerStockOutController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with(['customer','product']);

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('stock_out_date', [$request->start_date, $request->end_date]);
        }

        // Search Intelligence
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($cq) use ($search) {
                    $cq->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'LIKE', "%{$search}%");
                })->orWhere('type', 'LIKE', "%{$search}%")
                  ->orWhere('note', 'LIKE', "%{$search}%");
            });
        }

        $stockOuts = $query->orderByDesc('stock_out_date')->paginate(10)->withQueryString();
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();

        // Operational Intelligence Stats
        $stats = [
            'sold_qty' => StockOut::where('type','sale')->sum('quantity'),
            'current_balance' => DB::table('stock_in')->sum('quantity') - StockOut::sum('quantity'),
            'estimated_profit' => StockOut::where('type','sale')->sum(DB::raw('unit_price * quantity')),
            'returns_volume' => StockOut::where('type','return')->sum('quantity'),
        ];

        // Sales Pulse (Latest 7 days)
        $chartData = StockOut::where('type', 'sale')
            ->selectRaw('DATE(stock_out_date) as date, SUM(quantity * unit_price) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        return view('manager.stock_out.index', compact(
            'stockOuts','customers', 'products', 'stats', 'chartData'
        ));
    }

    /* -------------------------------------------------------------
     |   CUSTOMER SAVE
     ------------------------------------------------------------- */
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'nullable|email|max:255',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
        ]);

        if ($request->email && Customer::where('email', $request->email)->exists()) {
            return response()->json([
                'success'=>false,
                'code'=>'DUPLICATE',
                'message'=>'Email already exists!'
            ]);
        }

        $customer = Customer::create($request->only('name','email','phone','address'));

        return response()->json(['success'=>true,'customer'=>$customer]);
    }

    /* -------------------------------------------------------------
     |   STOCK OUT SAVE
     ------------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'=>'required|exists:customers,id',
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1',
            'stock_out_date'=>'required|date',
            'type'=>'required|in:sale,return',
        ]);

        // Calculate available stock
        $totalStockIn = DB::table('stock_in')
            ->where('product_id', $request->product_id)
            ->sum('quantity');

        $totalStockOut = StockOut::where('product_id', $request->product_id)
            ->sum('quantity');

        $availableStock = $totalStockIn - $totalStockOut;

        if ($request->type == 'sale' && $request->quantity > $availableStock) {
            return response()->json([
                'success'=>false,
                'message'=>"Not enough stock. Available: $availableStock"
            ], 400);
        }

        // Weighted Average Cost
        $stockIns = DB::table('stock_in')
            ->where('product_id',$request->product_id)
            ->get();

        $totalCost = $stockIns->sum(fn($s) => $s->unit_cost * $s->quantity);
        $totalQty  = $stockIns->sum('quantity');
        $unitPrice = $totalQty ? $totalCost / $totalQty : 0;

        // Save stock out
        $stockOut = StockOut::create([
            'customer_id'=>$request->customer_id,
            'product_id'=>$request->product_id,
            'quantity'=>$request->quantity,
            'unit_price'=>$unitPrice,
            'total_price'=>$request->quantity * $unitPrice,
            'type'=>$request->type,
            'stock_out_date'=>$request->stock_out_date,
            'note'=>$request->note ?? null,
            'user_id'=>Auth::id(),
        ]);

        // Also save sale record
        if ($request->type == 'sale') {
            Sale::create([
                'customer_id'=>$request->customer_id,
                'product_id'=>$request->product_id,
                'quantity'=>$request->quantity,
                'unit_price'=>$unitPrice,
                'total_price'=>$request->quantity * $unitPrice,
                'sale_date'=>$request->stock_out_date,
                'user_id'=>Auth::id(),
            ]);
        }

        return response()->json([
            'success'=>true,
            'stockOut'=>$stockOut->load(['customer','product'])
        ]);
    }

    /* -------------------------------------------------------------
     |   GET SINGLE STOCK OUT
     ------------------------------------------------------------- */
    public function getStockOutJson($id)
    {
        $stockOut = StockOut::with(['customer','product'])->findOrFail($id);
        return response()->json($stockOut);
    }

    /* -------------------------------------------------------------
     |   UPDATE STOCK OUT
     ------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity'=>'required|integer|min:1',
            'unit_price'=>'required|numeric|min:0',
            'type'=>'required|in:sale,return',
            'note'=>'nullable|string',
        ]);

        $stockOut = StockOut::findOrFail($id);

        // Available stock when editing
        $availableStock =
            DB::table('stock_in')->where('product_id',$stockOut->product_id)->sum('quantity')
            - StockOut::where('product_id',$stockOut->product_id)
                ->where('id','!=',$id)
                ->sum('quantity');

        if ($request->type == 'sale' && $request->quantity > $availableStock) {
            return response()->json([
                'success'=>false,
                'message'=>"Not enough stock. Available: $availableStock"
            ], 400);
        }

        $stockOut->update([
            'quantity'=>$request->quantity,
            'unit_price'=>$request->unit_price,
            'total_price'=>$request->quantity * $request->unit_price,
            'type'=>$request->type,
            'note'=>$request->note ?? $stockOut->note,
        ]);

        return response()->json([
            'success'=>true,
            'stockOut'=>$stockOut->load(['customer','product'])
        ]);
    }

    /* -------------------------------------------------------------
     |   DELETE
     ------------------------------------------------------------- */
    public function destroy($id)
    {
        $stockOut = StockOut::findOrFail($id);
        $stockOut->delete();

        return response()->json(['success'=>true,'message'=>'Deleted successfully']);
    }

    /* -------------------------------------------------------------
     |   CHECK AVAILABLE STOCK
     ------------------------------------------------------------- */
    public function checkStock($productId)
    {
        $availableStock =
            DB::table('stock_in')->where('product_id',$productId)->sum('quantity')
            - StockOut::where('product_id',$productId)->sum('quantity');

        return response()->json(['available'=>$availableStock]);
    }

    /* -------------------------------------------------------------
     |   GET PRODUCT PRICE (Weighted Avg)
     ------------------------------------------------------------- */
    public function getProductPrice($productId)
    {
        $stockIns = DB::table('stock_in')->where('product_id',$productId)->get();

        $totalCost = $stockIns->sum(fn($s)=>$s->unit_cost * $s->quantity);
        $totalQty  = $stockIns->sum('quantity');

        $unit_price = $totalQty ? $totalCost / $totalQty : 0;

        return response()->json(['unit_price'=>$unit_price]);
    }

    /* -------------------------------------------------------------
     |   PDF REPORT
     ------------------------------------------------------------- */
    public function exportPdf(Request $request)
    {
        $query = StockOut::with(['product', 'customer']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('stock_out_date', [$request->start_date, $request->end_date]);
        }

        $stockOuts = $query->orderBy('id', 'DESC')->get();

        $pdf = Pdf::loadView('manager.stock_out.report_pdf', compact('stockOuts'));
        return $pdf->download('stock_out_report.pdf');
    }


    /* -------------------------------------------------------------
     |   EXCEL REPORT
     ------------------------------------------------------------- */
    public function exportExcel(Request $request)
    {
        return Excel::download(new StockOutExport($request->start_date, $request->end_date), 'stockout_report.xlsx');
    }

    /* -------------------------------------------------------------
     |   AVAILABLE STOCK (for dropdown)
     ------------------------------------------------------------- */
    public function available()
    {
        $products = Product::withSum('stockIns','quantity')
            ->withSum('stockOuts','quantity')
            ->get()
            ->map(function($p){

                $in  = $p->stock_ins_sum_quantity ?? 0;
                $out = $p->stock_outs_sum_quantity ?? 0;

                $remaining = max($in - $out, 0);

                // Weighted average cost
                $stockIns = DB::table('stock_in')->where('product_id',$p->id)->get();
                $totalCost = $stockIns->sum(fn($s)=>$s->unit_cost * $s->quantity);
                $totalQty  = $stockIns->sum('quantity');
                $unitPrice = $totalQty ? $totalCost / $totalQty : 0;

                $status =
                    ($remaining == 0 ? "Out of Stock" :
                    ($remaining < 5 ? "Low Stock" : "Available"));

                return [
                    'id'=>$p->id,
                    'name'=>$p->name,
                    'remaining'=>$remaining,
                    'unit_price'=>$unitPrice,
                    'status'=>$status,
                ];
            });

        return response()->json($products);
    }

    /* -------------------------------------------------------------
     |   STOCK OVERVIEW (FULL SUMMARY)
     ------------------------------------------------------------- */
    public function stockOverview()
    {
        $products = Product::with(['stockIns','stockOuts'])->get();

        $result = $products->map(function($p){

            $total_in  = $p->stockIns->sum('quantity');
            $total_out = $p->stockOuts->sum('quantity');
            $current_stock = $total_in - $total_out;

            // Weighted average cost
            $totalCost = $p->stockIns->sum(fn($s)=>$s->unit_cost * $s->quantity);
            $totalQty  = $p->stockIns->sum('quantity');
            $unitPrice = $totalQty ? $totalCost / $totalQty : 0;

            $revenue = $total_out * $unitPrice;

            return [
                'id'=>$p->id,
                'name'=>$p->name,
                'total_in'=>$total_in,
                'total_out'=>$total_out,
                'current_stock'=>$current_stock,
                'unit_price'=>round($unitPrice,2),
                'revenue'=>round($revenue,2),
            ];
        });

        return response()->json($result);
    }

}
