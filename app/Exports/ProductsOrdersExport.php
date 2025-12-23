<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsOrdersExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new ProductsSheet(),
            new OrdersSheet($this->startDate, $this->endDate)
        ];
    }
}
