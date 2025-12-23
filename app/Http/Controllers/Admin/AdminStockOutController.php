<?php

namespace App\Http\Controllers\Admin;

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

class AdminStockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with(['customer','product'])
            ->orderByDesc('stock_out_date')
            ->paginate(15);

        $customers = Customer::all();
        $products  = Product::all();

        $totalStockIn = DB::table('stock_in')->sum('quantity');
        $totalSoldQuantity = StockOut::where('type','sale')->sum('quantity');

        // Profit = revenue from sales
        $profit = StockOut::where('type','sale')
            ->sum(DB::raw('unit_price * quantity'));

        // Loss = returns
        $loss = StockOut::where('type','return')
            ->sum(DB::raw('unit_price * quantity'));

        return view('admin.stock_out.index', compact(
            'stockOuts','customers','products','totalStockIn','totalSoldQuantity','profit','loss'
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
                'total_amount'=>$request->quantity * $unitPrice,
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
     |   PDF REPORT WITH DATE FILTER
     ------------------------------------------------------------- */
    public function reportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $query = StockOut::with(['customer','product']);

        if($startDate) $query->whereDate('stock_out_date','>=',$startDate);
        if($endDate) $query->whereDate('stock_out_date','<=',$endDate);

        $stockOuts = $query->get();

        $pdf = Pdf::loadView('admin.stock_out.report_pdf', compact('stockOuts'));
        return $pdf->download('stockout_report.pdf');
    }

    /* -------------------------------------------------------------
     |   EXCEL REPORT WITH DATE FILTER
     ------------------------------------------------------------- */
    public function reportExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        return Excel::download(new StockOutExport($startDate,$endDate), 'stockout_report.xlsx');
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

    /* -------------------------------------------------------------
     |   DAILY TRANSACTIONS (Today's Activity)
     ------------------------------------------------------------- */
    public function dailyTransactions()
    {
        $today = \Carbon\Carbon::today();
        
        $transactions = StockOut::with(['customer', 'product'])
            ->whereDate('stock_out_date', $today)
            ->orderByDesc('created_at')
            ->get();

        // Calculate today's metrics
        $todaySales = $transactions->where('type', 'sale');
        $todayReturns = $transactions->where('type', 'return');

        $totalRevenue = $todaySales->sum('total_price');
        $totalReturns = $todayReturns->sum('total_price');
        $totalQuantitySold = $todaySales->sum('quantity');
        $totalQuantityReturned = $todayReturns->sum('quantity');

        // Calculate actual profit (revenue - cost)
        $totalCost = 0;
        foreach ($todaySales as $sale) {
            // Get weighted average cost for this product
            $stockIns = \DB::table('stock_in')
                ->where('product_id', $sale->product_id)
                ->get();
            
            $productTotalCost = $stockIns->sum(fn($s) => $s->unit_cost * $s->quantity);
            $productTotalQty = $stockIns->sum('quantity');
            $avgCost = $productTotalQty ? $productTotalCost / $productTotalQty : 0;
            
            $totalCost += $avgCost * $sale->quantity;
        }

        $actualProfit = $totalRevenue - $totalCost;

        return response()->json([
            'transactions' => $transactions->map(function($t) {
                return [
                    'id' => $t->id,
                    'customer' => $t->customer->name ?? 'N/A',
                    'product' => $t->product->name ?? 'Unknown',
                    'quantity' => $t->quantity,
                    'unit_price' => $t->unit_price,
                    'total_price' => $t->total_price,
                    'type' => $t->type,
                    'time' => $t->created_at->format('H:i'),
                    'note' => $t->note,
                ];
            }),
            'summary' => [
                'total_transactions' => $transactions->count(),
                'total_sales' => $todaySales->count(),
                'total_returns' => $todayReturns->count(),
                'quantity_sold' => $totalQuantitySold,
                'quantity_returned' => $totalQuantityReturned,
                'revenue' => $totalRevenue,
                'returns_value' => $totalReturns,
                'cost' => $totalCost,
                'profit' => $actualProfit,
                'date' => $today->format('Y-m-d'),
            ]
        ]);
    }
}
