<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Product::with('category');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Strategic Label / Name',
            'Business Intelligence/Description',
            'Category Cluster',
            'Registration Timeline'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description ?? 'No strategic notes registered.',
            $product->category->name ?? 'GENERAL',
            $product->created_at->format('M d, Y H:i')
        ];
    }
}
