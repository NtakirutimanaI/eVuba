<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class SupportTicketsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Ticket::with(['customer', 'category', 'assignedUser']);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }
        if (!empty($this->filters['role'])) {
            $query->whereHas('assignedUser', function($q) {
                $q->role($this->filters['role']);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Ticket No',
            'Customer',
            'Subject',
            'Category',
            'Priority',
            'Status',
            'Assigned To',
            'Created At'
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_no,
            optional($ticket->customer)->name ?? 'Guest',
            $ticket->subject,
            optional($ticket->category)->name ?? '-',
            ucfirst($ticket->priority ?? 'normal'),
            ucfirst($ticket->status),
            optional($ticket->assignedUser)->name ?? 'Unassigned',
            $ticket->created_at->format('Y-m-d H:i')
        ];
    }
}
