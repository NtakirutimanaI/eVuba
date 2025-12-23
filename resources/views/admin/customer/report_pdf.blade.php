@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">Customers Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }}
        @if(isset($from) && isset($to))
            | <strong>Period:</strong> {{ $from }} to {{ $to }}
        @endif
        | <strong>Total:</strong> {{ count($customers ?? []) }}
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 25%;">Name</th>
            <th style="width: 25%;">Email</th>
            <th style="width: 15%;">Phone</th>
            <th style="width: 20%;">Address</th>
            <th style="width: 10%;">Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $index => $customer)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ $customer->address ?? '-' }}</td>
                <td>{{ $customer->created_at->format('Y-m-d') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 20px;">No customers found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">REPORT SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Customers:</span>
        <span class="summary-val">{{ count($customers ?? []) }}</span>
    </div>
</div>
@endsection
