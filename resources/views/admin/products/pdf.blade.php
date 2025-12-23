@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">Products Inventory Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }}
        @if(isset($start_date) && isset($end_date))
             | <strong>Period:</strong> {{ \Carbon\Carbon::parse($start_date)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($end_date)->format('M d, Y') }}
        @else
             | <strong>Full Inventory List</strong>
        @endif
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 30%;">Product Name</th>
            <th style="width: 20%;">Category</th>
            <th style="width: 15%; text-align: right;">Unit Price</th>
            <th style="width: 15%; text-align: center;">Stock</th>
            <th style="width: 15%;">Created</th>
        </tr>
    </thead>
    <tbody>
        @php $totalStock = 0; $totalValue = 0; @endphp
        @forelse($products as $index => $product)
            @php 
                $totalStock += $product->remaining_stock;
                $totalValue += ($product->remaining_stock * $product->unit_price);
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><b>{{ $product->name }}</b></td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td style="text-align: right;">{{ $product->unit_price ? number_format($product->unit_price, 2) . ' Frw' : '-' }}</td>
                <td style="text-align: center;">{{ $product->remaining_stock }}</td>
                <td>{{ $product->created_at->format('Y-m-d') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 20px;">No products found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">INVENTORY SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Items:</span>
        <span class="summary-val">{{ count($products) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Total Stock Quantity:</span>
        <span class="summary-val">{{ number_format($totalStock) }}</span>
    </div>
    <div class="summary-row total-highlight">
        <span class="summary-label">Total Stock Value:</span>
        <span class="summary-val">{{ number_format($totalValue, 2) }} Frw</span>
    </div>
</div>
@endsection
