<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StockOut;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Deduct stock for a paid order.
     *
     * @param Order $order
     * @return array ['success' => bool, 'message' => string]
     */
    public function deductStock(Order $order)
    {
        try {
            return DB::transaction(function () use ($order) {
                // 1. Check Stock Availability
                $totalStockIn = DB::table('stock_in')
                    ->where('product_id', $order->product_id)
                    ->sum('quantity');

                $totalStockOut = StockOut::where('product_id', $order->product_id)
                    ->sum('quantity');

                $availableStock = $totalStockIn - $totalStockOut;

                if ($order->quantity > $availableStock) {
                    return [
                        'success' => false,
                        'message' => "Insufficient stock! Available: $availableStock, Required: $order->quantity"
                    ];
                }

                // 2. Find or Create Customer Profile for the User
                $user = $order->user;
                $customer = null;

                if ($user) {
                    $customer = Customer::firstOrCreate(
                        ['email' => $user->email],
                        [
                            'name' => $user->name,
                            'phone' => 'N/A',
                            'address' => 'Created from Order #' . $order->id
                        ]
                    );
                } else {
                    // Fallback for guest orders if allowed, or error
                    return [
                        'success' => false,
                        'message' => "Order has no associated user."
                    ];
                }

                // 3. Calculate Unit Price (Weighted Average Cost)
                $stockIns = DB::table('stock_in')
                    ->where('product_id', $order->product_id)
                    ->get();

                $totalCost = $stockIns->sum(fn($s) => $s->unit_cost * $s->quantity);
                $totalQty = $stockIns->sum('quantity');
                $unitPrice = $totalQty ? $totalCost / $totalQty : 0;

                // Determine user ID describing "who performed this action"
                // If run from Admin controller, it's Auth::id(). 
                // If run from webhook/callback, it might be system or the user themselves.
                // We'll default to the order's user if not logged in (e.g. webhook)
                $actorId = Auth::id() ?? $order->user_id;

                // 4. Create Stock Out Record
                StockOut::create([
                    'customer_id' => $customer->id,
                    'product_id' => $order->product_id,
                    'quantity' => $order->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $order->quantity * $unitPrice,
                    'type' => 'sale',
                    'stock_out_date' => now(), // Or transaction date
                    'note' => "Auto-generated from Order #{$order->id}",
                    'user_id' => $actorId,
                ]);

                // 5. Create Sale Record
                Sale::create([
                    'customer_id' => $customer->id,
                    'product_id' => $order->product_id,
                    'quantity' => $order->quantity,
                    'unit_price' => $unitPrice,
                    'total_amount' => $order->quantity * $unitPrice,
                    'sale_date' => now(),
                    'user_id' => $actorId,
                ]);

                return ['success' => true, 'message' => 'Stock deducted successfully.'];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deducting stock: ' . $e->getMessage()
            ];
        }
    }
}
