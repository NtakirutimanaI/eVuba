<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection()
    {
        return Customer::whereBetween('created_at', [$this->from.' 00:00:00', $this->to.' 23:59:59'])
                       ->get()
                       ->map(function($customer){
            return [
                $customer->name,
                $customer->email,
                $customer->phone ?? '-',
                $customer->address ?? '-',
                $customer->created_at->format('Y-m-d H:i:s')
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Address', 'Created At'];
    }
}
