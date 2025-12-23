@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">Bookings Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }} |
        <strong>Total Bookings:</strong> {{ count($bookings) }}
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 20%;">Title</th>
            <th style="width: 30%;">Description</th>
            <th style="width: 15%;">Date</th>
            <th style="width: 15%;">Booked By</th>
            <th style="width: 15%; text-align: center;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings as $index => $b)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $b->title }}</td>
                <td>{{ Str::limit($b->description, 50) }}</td>
                <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('Y-m-d') : '-' }}</td>
                <td>{{ $b->user->name ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    <span style="text-transform: capitalize; padding: 2px 4px; border-radius: 4px; background: {{ $b->status == 'approved' ? '#dcfce7' : ($b->status == 'pending' ? '#fef3c7' : '#fee2e2') }}; font-size: 10px;">
                        {{ ucfirst($b->status) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 20px;">No bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">BOOKINGS SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Bookings:</span>
        <span class="summary-val">{{ count($bookings) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Pending:</span>
        <span class="summary-val">{{ $bookings->where('status', 'pending')->count() }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Approved:</span>
        <span class="summary-val">{{ $bookings->where('status', 'approved')->count() }}</span>
    </div>
</div>
@endsection
