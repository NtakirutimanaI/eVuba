<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Appointment Audit Report</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; background: #fff; margin: 0; padding: 0; color: #1e293b; line-height: 1.5; }
        .header-stripe { height: 8px; background: #6366f1; width: 100%; }
        .container { padding: 40px; }
        
        .report-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; }
        .company-info .brand { font-size: 24px; font-weight: 800; color: #6366f1; letter-spacing: -1px; }
        .company-info .sub { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 2px; margin-top: 4px; }
        
        .report-meta { text-align: right; }
        .report-meta .title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }
        .report-meta .details { font-size: 11px; color: #94a3b8; margin-top: 5px; }

        .stats-grid { display: block; margin-bottom: 30px; }
        .stat-box { display: inline-block; width: 22%; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; margin-right: 2%; }
        .stat-box:last-child { margin-right: 0; }
        .stat-box .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        .stat-box .value { font-size: 16px; font-weight: 800; color: #1e293b; margin-top: 5px; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8fafc; color: #475569; text-transform: uppercase; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; padding: 12px 15px; border-bottom: 2px solid #e2e8f0; text-align: left; }
        td { padding: 12px 15px; font-size: 11px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        tr:nth-child(even) { background: #fafbfc; }
        
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-confirmed { background: #dbeafe; color: #1e40af; }
        .badge-assigned { background: #e0e7ff; color: #3730a3; }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-canceled { background: #fee2e2; color: #991b1b; }

        .footer { position: fixed; bottom: 30px; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .footer .page-number:before { content: "Page " counter(page); }
    </style>
</head>
<body>
    <div class="header-stripe"></div>
    <div class="container">
        <div class="report-header">
            <div class="company-info" style="float: left;">
                <div class="brand">eVuba Distribution</div>
                <div class="sub">Strategic Service Management</div>
            </div>
            <div class="report-meta" style="float: right;">
                <h1 class="title">Tactical Registry Audit</h1>
                <div class="details">Generation Timestamp: {{ now()->format('M d, Y @ h:i A') }}</div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <div class="label">Total Records</div>
                <div class="value">{{ count($appointments) }}</div>
            </div>
            <div class="stat-box">
                <div class="label">Settled Ops</div>
                <div class="value">{{ count($appointments->where('status', 'completed')) }}</div>
            </div>
            <div class="stat-box">
                <div class="label">Active Review</div>
                <div class="value">{{ count($appointments->where('status', 'pending')) }}</div>
            </div>
            <div class="stat-box">
                <div class="label">Registry Valuation</div>
                <div class="value">Level A Audit</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>REF</th>
                    <th>Client Entity</th>
                    <th>Tactical engagement</th>
                    <th>Status</th>
                    <th>Agent</th>
                    <th>Scheduled Window</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $a)
                <tr>
                    <td style="font-weight: 700;">#{{ $a->id }}</td>
                    <td>
                        <div style="font-weight: 700;">{{ $a->user->name ?? 'Direct Client' }}</div>
                        <div style="font-size: 9px; color: #64748b;">{{ $a->user->email ?? 'no-digital-trace' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $a->title }}</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $a->status }}">
                            {{ $a->status }}
                        </span>
                    </td>
                    <td>{{ $a->employee->name ?? 'Unassigned' }}</td>
                    <td>
                        <div style="font-weight: 700;">{{ \Carbon\Carbon::parse($a->scheduled_at)->format('M d, Y') }}</div>
                        <div style="font-size: 9px; color: #64748b;">{{ \Carbon\Carbon::parse($a->scheduled_at)->format('h:i A') }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <div style="margin-bottom: 5px;">This document is an official registry extract from the eVuba Tactical Management System.</div>
            <div class="page-number"></div>
        </div>
    </div>
</body>
</html>
