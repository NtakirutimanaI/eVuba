<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Distribution Ledger Report - eVuba</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 3px solid #6366f1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1e1b4b;
            margin: 0;
            font-size: 26px;
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
            padding: 20px;
            border-radius: 12px;
        }

        .meta-grid td {
            padding: 5px 0;
            font-size: 12px;
            color: #475569;
        }

        .meta-label {
            font-weight: bold;
            color: #1e293b;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 12px 15px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 12px 15px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-sale {
            background: #dcfce7;
            color: #166534;
        }

        .status-return {
            background: #fee2e2;
            color: #991b1b;
        }

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
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .summary-item {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
        }

        .text-right {
            text-align: right;
        }

        .brand-accent {
            color: #6366f1;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Distribution <span class="brand-accent">Intelligence</span> Report</h1>
        <p>Strategic Inventory Despatch & Revenue Realization Ledger</p>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-label">Generated On:</td>
            <td>{{ now()->format('M d, Y H:i') }}</td>
            <td class="meta-label">Authority:</td>
            <td>{{ auth()->user()->name ?? 'System Manager' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Ledger Scope:</td>
            <td>Full Distribution Audit</td>
            <td class="meta-label">Entity:</td>
            <td>eVuba Solutions Hub</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>REF</th>
                <th>Strategic Partner</th>
                <th>Product Entity</th>
                <th>Volume</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total (FRW)</th>
                <th>Logic</th>
                <th>Timeline</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRevenue = 0; @endphp
            @forelse($stockOuts as $out)
                @php $totalRevenue += ($out->quantity * $out->unit_price); @endphp
                <tr>
                    <td>#{{ $out->id }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $out->customer->name ?? 'N/A' }}</div>
                        <div style="font-size: 9px; color: #64748b;">{{ $out->customer->phone ?? '' }}</div>
                    </td>
                    <td>{{ $out->product->name ?? 'N/A' }}</td>
                    <td style="font-weight: bold;">{{ $out->quantity }}</td>
                    <td class="text-right">{{ number_format($out->unit_price, 0) }}</td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ number_format($out->quantity * $out->unit_price, 0) }}</td>
                    <td>
                        <span class="status-badge {{ $out->type == 'sale' ? 'status-sale' : 'status-return' }}">
                            {{ $out->type }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($out->stock_out_date)->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">No distribution entries
                        found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr>
            <td style="width: 60%; vertical-align: bottom; border: none; padding: 0;">
                <div style="font-size: 10px; color: #94a3b8; margin-bottom: 5px; text-transform: uppercase;">Generated
                    By:</div>
                <div style="font-size: 14px; font-weight: bold; color: #1e293b;">
                    {{ auth()->user()->name ?? 'System Manager' }}</div>
                <div style="font-size: 11px; color: #64748b; margin-bottom: 20px;">
                    {{ auth()->user()->role ?? 'Official' }}</div>
                <div style="border-bottom: 1px solid #e2e8f0; width: 200px; margin-bottom: 5px;"></div>
                <div style="font-size: 10px; color: #94a3b8;">Authorized Signature</div>
            </td>
            <td style="width: 40%; vertical-align: top; border: none; padding: 0;">
                <div class="summary-box" style="margin-top: 0; border-top: none;">
                    <div class="summary-item" style="border-top: 2px solid #e2e8f0; padding-top: 15px;">
                        <span style="color: #64748b; font-size: 12px; font-weight: normal; margin-right: 15px;">TOTAL
                            DESPATCH VALUATION</span>
                        <div style="margin-top: 5px;">FRW {{ number_format($totalRevenue, 0) }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        This document is an official record of distribution. Generated via eVuba Intelligence Dashboard. &copy;
        {{ date('Y') }} eVuba.
    </div>
</body>

</html>