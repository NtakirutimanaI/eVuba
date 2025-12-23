<?php

namespace App\Exports;

use App\Models\StockIn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockInExport implements FromCollection, WithHeadings, WithMapping
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
        $query = StockIn::with(['product', 'supplier', 'user']);

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('stock_in_date', [$this->start_date, $this->end_date]);
        }

        return $query->orderBy('stock_in_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product SKU',
            'Supplier/Source',
            'Volume',
            'Unit Cost (FRW)',
            'Total Valuation (FRW)',
            'Acquisition Type',
            'Timeline',
            'Orchestrated By'
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->id,
            $stock->product->name ?? 'N/A',
            $stock->supplier->name ?? 'Internal / N/A',
            $stock->quantity,
            $stock->unit_cost,
            $stock->total_cost,
            ucfirst($stock->type),
            $stock->stock_in_date->format('M d, Y'),
            $stock->user->name ?? 'System'
        ];
    }
}
