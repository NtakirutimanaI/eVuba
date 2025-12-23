<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersSheet implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Order::with('customer');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->get()->map(function($order){
            return [
                'Customer' => $order->customer->name ?? 'Unknown',
                'Email' => $order->customer->email ?? '-',
                'Product' => $order->product_name,
                'Quantity' => $order->quantity,
                'Price' => $order->price,
                'Total' => $order->price * $order->quantity,
                'Status' => $order->status,
                'Date' => $order->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Customer','Email','Product','Quantity','Price','Total','Status','Date'];
    }
}
