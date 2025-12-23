<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Ticket::with(['customer', 'category', 'assignedUser'])
                     ->orderBy('created_at', 'desc')
                     ->get()
                     ->map(function($ticket){
                        return [
                            'Ticket No'     => $ticket->ticket_no,
                            'Customer'      => optional($ticket->customer)->name,
                            'Category'      => optional($ticket->category)->name,
                            'Assigned To'   => optional($ticket->assignedUser)->name ?? 'Unassigned',
                            'Priority'      => $ticket->priority,
                            'Status'        => ucfirst($ticket->status),
                            'Created At'    => $ticket->created_at->format('Y-m-d H:i'),
                            'Updated At'    => $ticket->updated_at->format('Y-m-d H:i'),
                        ];
                     });
    }

    public function headings(): array
    {
        return [
            'Ticket No',
            'Customer',
            'Category',
            'Assigned To',
            'Priority',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}
