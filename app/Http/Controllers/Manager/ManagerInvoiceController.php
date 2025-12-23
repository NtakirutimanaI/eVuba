<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;

class ManagerInvoiceController extends Controller
{
    /**
     * Display all invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('items.product', 'customer')->latest()->paginate(20);
        return view('manager.stock_out.index', compact('invoices'));
    }

    /**
     * Show form to create a new invoice.
     */
    public function create()
    {
        $customers = Customer::all();

        // Fetch products and calculate current stock
        $products = Product::all()->map(function($p) {
            $stockInQty = StockIn::where('product_id', $p->id)->sum('quantity');
            $stockOutQty = StockOut::where('product_id', $p->id)->sum('quantity');
            $p->current_stock = $stockInQty - $stockOutQty;
            return $p;
        });

        return view('manager.stock_out.invoice_create', compact('customers', 'products'));
    }

    /**
     * Store a new invoice with items.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'         => 'required|exists:customers,id',
            'items'               => 'required|array|min:1',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.qty'         => 'required|numeric|min:1',
            'items.*.price'       => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check current stock dynamically
                $stockInQty  = StockIn::where('product_id', $product->id)->sum('quantity');
                $stockOutQty = StockOut::where('product_id', $product->id)->sum('quantity');
                $currentStock = $stockInQty - $stockOutQty;

                if ($item['qty'] > $currentStock) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Quantity for {$product->name} exceeds available stock!"
                    ], 400);
                }

                $subtotal = $item['qty'] * $item['price'];
                $totalAmount += $subtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'total'      => $subtotal,
                ]);

                // Update StockOut table
                StockOut::create([
                    'product_id' => $product->id,
                    'quantity'   => $item['qty'],
                    'invoice_id' => $invoice->id,
                    'user_id'    => auth()->id(),
                    'stock_out_date' => now(),
                    'type'       => 'sale',
                ]);
            }

            $invoice->update(['total_amount' => $totalAmount]);
            DB::commit();

            return response()->json(['success' => true, 'invoice_id' => $invoice->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Payment processing.
     */
    public function payment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'method' => 'required|string',
            'data'   => 'required|array'
        ]);

        $invoice->payment_method = $request->method;
        $invoice->payment_data   = json_encode($request->data);
        $invoice->is_paid        = true;
        $invoice->save();

        return response()->json(['success' => true]);
    }

    /**
     * Print invoice
     */
    public function print($id)
    {
        $invoice = Invoice::with('items.product', 'customer')->findOrFail($id);
        return view('manager.stock_out.print', compact('invoice'));
    }
}
