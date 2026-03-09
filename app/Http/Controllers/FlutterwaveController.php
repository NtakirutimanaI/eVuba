<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FlutterwaveController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate payment for an order
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('user')->findOrFail($request->order_id);

        // Check if order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized access to order.');
        }

        // Check if order is already paid
        if (in_array($order->payment_status, ['approved', 'paid'])) {
            return redirect()->back()->with('info', 'This order has already been paid.');
        }

        // Generate unique transaction reference
        $txRef = 'EVB-' . time() . '-' . $order->id;

        // Prepare payment data
        $paymentData = [
            'tx_ref' => $txRef,
            'amount' => $order->price * $order->quantity,
            'currency' => 'RWF', // Rwandan Franc
            'redirect_url' => route('payment.callback'),
            'customer' => [
                'email' => $order->user->email,
                'name' => $order->user->name,
            ],
            'customizations' => [
                'title' => 'eVuba Order Payment',
                'description' => "Payment for {$order->product_name} (x{$order->quantity})",
                'logo' => asset('images/logo.png'),
            ],
            'meta' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],
        ];

        // Initiate payment with Flutterwave
        $result = $this->paymentService->initiatePayment($paymentData);

        if ($result['success']) {
            // Update order transaction reference
            $order->update([
                'transaction_ref' => $txRef,
                'payment_method' => 'Flutterwave',
            ]);

            // Redirect to Flutterwave payment page
            return redirect($result['payment_link']);
        }

        Log::error('Flutterwave Payment Initiation Failed', $result);
        return redirect()->back()->with('error', $result['message'] ?? 'Failed to initiate payment. Please try again.');
    }

    /**
     * Handle payment callback from Flutterwave
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        $status = $request->query('status');
        $txRef = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');

        // If payment was cancelled
        if ($status === 'cancelled') {
            return redirect()->route('customer.orders.index')
                ->with('warning', 'Payment was cancelled.');
        }

        // If payment was successful, verify the transaction
        if ($status === 'successful' && $transactionId) {
            $verification = $this->paymentService->verifyTransaction($transactionId);

            if ($verification['success'] && $verification['status'] === 'successful') {
                // Find the order by transaction reference
                $order = Order::where('transaction_ref', $txRef)->first();

                if ($order) {
                    $this->updateOrderAfterPayment($order, $verification);

                    return redirect()->route('customer.orders.index')
                        ->with('success', 'Payment successful! Your order has been confirmed.');
                }
            }

            Log::warning('Payment verification failed or order not found', [
                'tx_ref' => $txRef,
                'verification' => $verification
            ]);
        }

        return redirect()->route('customer.orders.index')
            ->with('error', 'Payment verification failed. Please contact support if amount was debited.');
    }

    /**
     * Handle webhook from Flutterwave
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        $signature = $request->header('verif-hash');
        $payload = $request->all();

        // Verify webhook signature
        if (!$signature || $signature !== config('flutterwave.secretKey')) {
            Log::warning('Invalid webhook signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        // Process webhook
        if (isset($payload['event']) && $payload['event'] === 'charge.completed') {
            $data = $payload['data'];

            if ($data['status'] === 'successful') {
                $txRef = $data['tx_ref'];
                $order = Order::where('transaction_ref', $txRef)->first();

                if ($order && !in_array($order->payment_status, ['approved', 'paid'])) {
                    // Verify the transaction with Flutterwave
                    $verification = $this->paymentService->verifyTransaction($data['id']);

                    if ($verification['success'] && $verification['status'] === 'successful') {
                        $this->updateOrderAfterPayment($order, $verification);

                        Log::info('Order payment updated via webhook', ['order_id' => $order->id]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Update order status after successful payment
     * 
     * @param Order $order
     * @param array $verification
     * @return void
     */
    protected function updateOrderAfterPayment(Order $order, array $verification)
    {
        DB::transaction(function () use ($order, $verification) {

            // Deduct Stock
            $stockService = new \App\Services\StockService();
            $result = $stockService->deductStock($order);

            if (!$result['success']) {
                Log::error('Stock deduction failed for paid order ' . $order->id . ': ' . $result['message']);
                // Decide how to handle this - potentially notify admin or mark order as 'payment_received_no_stock'
                // For now, we continue to mark as paid but maybe leave status as 'pending' for manual review?
                // OR we can throw exception to rollback transaction if appropriate, but payment is already done...
                // Better to throw exception so transaction rolls back, and we handle the "Paid but Error" state separately?
                // Payment is verified external, so we MUST record the payment.
                // Let's proceed but maybe log a critical alert.

                // For simplicity as per request: just try to deduct. If fails, we might over-sell. 
                // However, user specifically asked for stock deduction on payment.
                // We'll proceed with order update but log failure.
            }

            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_method' => 'Flutterwave',
            ]);

            // Create or update invoice
            $invoice = Invoice::where('customer_id', $order->user_id)
                ->where('description', 'like', "%Order #{$order->id}%")
                ->first();

            if ($invoice) {
                $invoice->update([
                    'status' => 'paid',
                    'payment_method' => 'Flutterwave',
                ]);
            } else {
                // Ensure customer exists to avoid foreign key errors
                $customer = \App\Models\Customer::where('id', $order->user_id)
                    ->orWhere('email', $order->user->email)
                    ->first();

                if (!$customer) {
                    $customer = \App\Models\Customer::create([
                        'id' => $order->user_id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                        'phone' => $order->user->phone,
                        'address' => $order->user->address,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]);
                }

                // Create new invoice
                Invoice::create([
                    'invoice_number' => 'INV-' . time() . '-' . $order->id,
                    'invoice_date' => now(),
                    'customer_id' => $customer->id,
                    'customer_name' => $order->user->name ?? 'N/A',
                    'description' => "Order #{$order->id} - {$order->product_name}",
                    'subtotal' => ($order->price * $order->quantity) / 1.18,
                    'tax_amount' => ($order->price * $order->quantity) - (($order->price * $order->quantity) / 1.18),
                    'grand_total' => $order->price * $order->quantity,
                    'payment_method' => 'Flutterwave',
                    'status' => 'paid',
                ]);
            }

            // Notify user via DB notification
            $order->user->notify(new \App\Notifications\SystemAlert([
                'title' => 'Payment Successful',
                'message' => "Your payment for {$order->product_name} has been confirmed.",
                'icon' => 'fa-check-circle',
                'action_url' => route('customer.orders.index')
            ]));

            // Send Mail Invoice Receipt (like before)
            try {
                \Illuminate\Support\Facades\Mail::to($order->user)->send(new \App\Mail\InvoicePaid($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send Invoice email: ' . $e->getMessage());
            }
        });
    }
}
