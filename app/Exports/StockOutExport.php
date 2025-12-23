<?php

namespace App\Exports;

use App\Models\StockOut;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockOutExport implements FromCollection, WithHeadings, WithMapping
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
        $query = StockOut::with(['customer', 'product', 'user']);

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('stock_out_date', [$this->start_date, $this->end_date]);
        }

        return $query->orderBy('stock_out_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Strategic Partner',
            'Product Entity',
            'Volume',
            'Unit Valuation (FRW)',
            'Total Despatch (FRW)',
            'Logic Type',
            'Timeline',
            'Orchestrated By',
            'Strategy Notes'
        ];
    }

    public function map($out): array
    {
        return [
            $out->id,
            $out->customer->name ?? 'N/A',
            $out->product->name ?? 'N/A',
            $out->quantity,
            $out->unit_price,
            $out->quantity * $out->unit_price,
            ucfirst($out->type),
            $out->stock_out_date,
            $out->user->name ?? 'System',
            $out->note
        ];
    }
}
