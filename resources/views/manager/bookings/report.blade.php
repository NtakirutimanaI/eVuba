<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bookings Portfolio Report</title>
    <style>
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #333; 
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #6366f1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e1b4b;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            color: #64748b;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .meta-grid {
            width: 100%;
            margin-bottom: 30px;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }
        .meta-grid td { padding: 5px 0; font-size: 12px; color: #475569; }
        .meta-label { font-weight: bold; color: #1e293b; width: 120px; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        th { 
            background-color: #f1f5f9; 
            color: #475569; 
            font-weight: bold; 
            text-align: left;
            padding: 12px 10px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        td { 
            padding: 10px; 
            font-size: 11px; 
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-completed { background: #e0f2fe; color: #075985; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            padding: 20px 0;
            border-top: 1px solid #f1f5f9;
        }
        .summary-box {
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e2e8f0;
        }
        .summary-item {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bookings Ledger Report</h1>
        <p>Operational Performance & Service Distribution</p>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-label">Generated On:</td>
            <td>{{ now()->format('M d, Y H:i') }}</td>
            <td class="meta-label">Report Period:</td>
            <td>
                @if(request('start_date') && request('end_date'))
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                @elseif(request('start_date'))
                    From {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }}
                @elseif(request('end_date'))
                    Until {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                @else
                    Full Portfolio History
                @endif
            </td>
        </tr>
        <tr>
            <td class="meta-label">Total Records:</td>
            <td>{{ count($bookings) }} bookings</td>
            <td class="meta-label">Data Scope:</td>
            <td>Institutional Service Ledger</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Booking Details</th>
                <th>Schedule</th>
                <th>Stakeholders</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td style="width: 35%;">
                    <div style="font-weight: bold; color: #1e293b;">{{ $booking->title }}</div>
                    <div style="color: #64748b; font-size: 10px; margin-top: 3px;">{{ Str::limit($booking->description, 60) }}</div>
                </td>
                <td style="width: 20%;">
                    <div>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                    <div style="color: #94a3b8; font-size: 9px;">Arrival Date</div>
                </td>
                <td style="width: 25%;">
                    <div><strong>Client:</strong> {{ $booking->user->name ?? 'N/A' }}</div>
                    <div style="color: #64748b; font-size: 9px;"><strong>Expert:</strong> {{ $booking->employee->name ?? 'Unassigned' }}</div>
                </td>
                <td style="width: 20%;">
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ $booking->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-item">Total Volume: {{ count($bookings) }} Services Handled</div>
    </div>

    <div class="footer">
        Confidential Operations Report - Generated via eVuba Management Suite
    </div>
</body>
</html>
