<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsSheet implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('category')->get()->map(function($product){
            return [
                'Name' => $product->name,
                'Code' => $product->product_code ?? '-',
                'Category' => $product->category->name ?? '-',
                'Status' => $product->status ?? 'Unpublished',
                'Description' => $product->description ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Name','Code','Category','Status','Description'];
    }
}
