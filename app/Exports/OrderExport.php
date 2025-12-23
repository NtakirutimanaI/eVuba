<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $query = Order::with(['customer', 'product']);

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Product Entity',
            'Quantity',
            'Unit Price (FRW)',
            'Total Price (FRW)',
            'Status',
            'Timeline'
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->customer->name ?? 'Guest',
            $order->product_name ?? ($order->product->name ?? 'N/A'),
            $order->quantity,
            $order->price,
            $order->quantity * $order->price,
            ucfirst($order->status),
            $order->created_at->format('M d, Y H:i')
        ];
    }
}
