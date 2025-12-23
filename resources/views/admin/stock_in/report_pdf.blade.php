@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">Stock In Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }}
        @if(isset($start) && isset($end))
            | <strong>Period:</strong> {{ $start }} to {{ $end }}
        @endif
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 20%;">Product</th>
            <th style="width: 15%;">Supplier</th>
            <th style="width: 10%; text-align: center;">Qty</th>
            <th style="width: 15%; text-align: right;">Unit Cost</th>
            <th style="width: 15%; text-align: right;">Total Cost</th>
            <th style="width: 10%; text-align: center;">Type</th>
            <th style="width: 10%;">Date</th>
        </tr>
    </thead>
    <tbody>
        @php $grandTotal = 0; $totalQty = 0; @endphp
        @forelse($stockIns as $index => $stock)
            @php 
                $grandTotal += $stock->total_cost;
                $totalQty += $stock->quantity;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $stock->product->name ?? 'Deleted' }}</td>
                <td>{{ $stock->supplier->name ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $stock->quantity }}</td>
                <td style="text-align: right;">{{ number_format($stock->unit_cost, 2) }}</td>
                <td style="text-align: right;">{{ number_format($stock->total_cost, 2) }}</td>
                <td style="text-align: center; text-transform: capitalize;">{{ $stock->type }}</td>
                <td>{{ $stock->stock_in_date ? $stock->stock_in_date->format('Y-m-d') : '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 20px;">No stock entries found for this report.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">FINANCIAL SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Records:</span>
        <span class="summary-val">{{ count($stockIns) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Total Quantity:</span>
        <span class="summary-val">{{ number_format($totalQty) }}</span>
    </div>
    <div class="summary-row total-highlight">
        <span class="summary-label">Grand Total Cost:</span>
        <span class="summary-val">{{ number_format($grandTotal, 2) }} FRW</span>
    </div>
</div>
@endsection
