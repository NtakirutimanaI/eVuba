@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">Support Intelligence Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }}
        @if(isset($start) && isset($end))
            | <strong>Period:</strong> {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
        @endif
        | <strong>Total Tickets:</strong> {{ $tickets->count() }}
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 15%;">ID / Date</th>
            <th style="width: 30%;">Description</th>
            <th style="width: 20%;">Client</th>
            <th style="width: 15%;">Responsible</th>
            <th style="width: 15%; text-align: center;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tickets as $ticket)
            <tr>
                <td>
                    <div style="font-weight: bold; color: #4f46e5;">#{{ $ticket->ticket_no }}</div>
                    <div style="font-size: 10px; color: #64748b;">{{ $ticket->created_at->format('d M Y') }}</div>
                </td>
                <td>
                    <div style="font-weight: bold;">{{ $ticket->subject }}</div>
                    <div style="font-size: 10px; color: #64748b;">
                        {{ optional($ticket->category)->name ?? '-' }} | 
                        <span style="color: {{ $ticket->priority == 'high' ? 'red' : 'blue' }}">{{ strtoupper($ticket->priority ?? 'normal') }}</span>
                    </div>
                </td>
                <td>
                    {{ optional($ticket->customer)->name ?? 'Guest' }}<br>
                    <span style="font-size: 10px; color: #64748b;">{{ optional($ticket->customer)->email ?? '-' }}</span>
                </td>
                <td>{{ optional($ticket->assignedUser)->name ?? '---' }}</td>
                <td style="text-align: center;">
                    <span style="text-transform: capitalize; padding: 2px 4px; border-radius: 4px; background: {{ $ticket->status == 'open' ? '#fee2e2' : ($ticket->status == 'closed' ? '#dcfce7' : '#fef3c7') }}; font-size: 10px;">
                        {{ str_replace('_', ' ', $ticket->status) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center; padding: 20px;">No matching support data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">TICKET SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Tickets:</span>
        <span class="summary-val">{{ $tickets->count() }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Open:</span>
        <span class="summary-val">{{ $tickets->where('status', 'open')->count() }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Closed:</span>
        <span class="summary-val">{{ $tickets->where('status', 'closed')->count() }}</span>
    </div>
</div>
@endsection
