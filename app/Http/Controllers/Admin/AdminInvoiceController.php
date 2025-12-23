<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use PDF;

class AdminInvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);
        return view('admin.stock_out.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {
        // Fetch all products and customers to display in the invoice form
        $products = \App\Models\Product::all();
        $customers = \App\Models\Customer::all();

        return view('admin.stock_out.invoice', compact('products', 'customers'));
    }


    /**
     * Store a newly created invoice in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id'   => 'nullable|exists:customers,id',
            'invoice_date'  => 'required|date',
            'subtotal'      => 'required|numeric',
            'tax_amount'    => 'required|numeric',
            'grand_total'   => 'required|numeric',
            'payment_method'=> 'nullable|string',
            'status'        => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.name'  => 'required|string',
            'items.*.qty'   => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Auto-generate invoice number
            $invoiceNumber = 'INV-' . str_pad((Invoice::max('id') + 1), 5, '0', STR_PAD_LEFT);

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $request->customer_id,
                'customer_name'  => $request->customer_name,
                'invoice_date'   => $request->invoice_date,
                'subtotal'       => $request->subtotal,
                'tax_amount'     => $request->tax_amount,
                'grand_total'    => $request->grand_total,
                'payment_method' => $request->payment_method ?? 'Cash',
                'status'         => $request->status ?? 'pending',
                'description'    => $request->description,
            ]);

            // Insert invoice items
            foreach ($request->items as $id => $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => is_numeric($id) ? $id : null,
                    'description' => $item['name'],
                    'quantity'    => $item['qty'],
                    'unit_price'  => $item['price'],
                    'total'       => $item['qty'] * $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully.',
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified invoice
     */
    public function show($id)
    {
        $invoice = Invoice::with(['items', 'customer'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Remove the specified invoice from storage
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Delete related items first
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Download invoice as PDF
     */
    public function pdf($id)
    {
        $invoice = Invoice::with(['items', 'customer'])->findOrFail($id);

        $pdf = PDF::loadView('admin.invoices.invoice-pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Save invoice with payment and generate PDF
     */
    public function saveInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad((Invoice::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT);

            // Get customer
            $customer = \App\Models\Customer::findOrFail($request->customer_id);

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer_id,
                'customer_name' => $customer->name,
                'invoice_date' => now(),
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax,
                'grand_total' => $request->total,
                'payment_method' => $request->payment_method,
                'status' => 'paid',
                'description' => $request->notes ?? null,
            ]);

            // Create invoice items and stock out records
            foreach ($request->items as $item) {
                // Create invoice item
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['productId'],
                    'description' => $item['productName'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);

                // Create stock out record
                \App\Models\StockOut::create([
                    'customer_id' => $request->customer_id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                    'type' => 'sale',
                    'stock_out_date' => now(),
                    'note' => 'Invoice: ' . $invoiceNumber,
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            // Generate PDF URL
            $pdfUrl = route('admin.invoices.pdf', $invoice->id);

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'pdf_url' => $pdfUrl
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating invoice: ' . $e->getMessage()
            ], 500);
        }
    }

}
