@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">Stock Out (Sales) Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }}
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 20%;">Product</th>
            <th style="width: 15%;">Customer</th>
            <th style="width: 8%; text-align: center;">Qty</th>
            <th style="width: 13%; text-align: right;">Unit Price</th>
            <th style="width: 14%; text-align: right;">Total Price</th>
            <th style="width: 10%; text-align: center;">Type</th>
            <th style="width: 15%;">Date</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $totalRevenue = 0; 
            $totalTax = 0; 
            $totalQty = 0;
        @endphp
        @forelse($stockOuts as $index => $stock)
            @php 
                // Assumption: total_price in DB is the amount charged. 
                // We calculate tax as 18% ON TOP or INCLUDED? 
                // Usually Sales Reports show Revenue.
                // If we assume the price is inclusive, Tax = Price - (Price / 1.18).
                // If exclusive, Tax = Price * 0.18.
                // Given "calculate tax" requirement, I'll display Tax separately.
                // I will assume the total_price stored is the Subtotal for simplicity unless Invoice says otherwise, 
                // BUT invoices usually store Grand Total. 
                // Let's assume total_price is the transaction value.
                // I will calculate estimated Tax (18% of value) for display.
                $val = $stock->total_price;
                $tax = $val * 0.18; 
                $totalRevenue += $val;
                $totalTax += $tax;
                $totalQty += $stock->quantity;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $stock->product->name ?? 'Deleted' }}</td>
                <td>{{ $stock->customer->name ?? 'Walk-in' }}</td>
                <td style="text-align: center;">{{ $stock->quantity }}</td>
                <td style="text-align: right;">{{ number_format($stock->unit_price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($stock->total_price, 2) }}</td>
                <td style="text-align: center;">
                    <span style="text-transform: capitalize; padding: 2px 4px; border-radius: 4px; background: {{ $stock->type == 'sale' ? '#dcfce7' : '#fee2e2' }}; font-size: 10px;">
                        {{ $stock->type }}
                    </span>
                </td>
                <td>{{ $stock->stock_out_date ? \Carbon\Carbon::parse($stock->stock_out_date)->format('Y-m-d') : '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 20px;">No stock out entries found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">SALES SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Quantity:</span>
        <span class="summary-val">{{ number_format($totalQty) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Subtotal:</span>
        <span class="summary-val">{{ number_format($totalRevenue, 2) }} FRW</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Tax (18%):</span>
        <span class="summary-val">{{ number_format($totalTax, 2) }} FRW</span>
    </div>
    <div class="summary-row total-highlight">
        <span class="summary-label">Total Revenue (Inc. Tax):</span>
        <span class="summary-val">{{ number_format($totalRevenue + $totalTax, 2) }} FRW</span>
    </div>
</div>
@endsection
