<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }} - eVuba</title>
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
            border-bottom: 3px solid #0056b3;
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

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-progress {
            background: #dbeafe;
            color: #1e40af;
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
        <table style="width: 100%; margin-bottom: 30px; padding-bottom: 10px;">
            <tr>
                <td style="text-align: left; vertical-align: middle; border: none;">
                    <div
                        style="background-color: #00C853; color: white; padding: 10px 20px; border-radius: 4px; font-weight: bold; font-family: sans-serif; font-size: 24px; display: inline-block; position: relative;">
                        Receipt
                        <span
                            style="position: absolute; top: -10px; right: -10px; background: white; color: #00C853; border-radius: 50%; width: 20px; height: 20px; text-align: center; line-height: 20px; font-size: 14px; border: 2px solid #00C853;">&#10003;</span>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle; border: none;">
                    <div style="color: #0056b3; font-weight: bold; font-size: 32px; font-family: sans-serif;">
                        eVubaConnect</div>
                    <div style="color: #64748b; font-size: 12px; margin-top: 5px; font-weight: bold;">
                        #{{ $order->transaction_ref ?? 'INV-' . str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-label">Invoice Ref:</td>
            <td style="font-weight: bold;">
                {{ $order->transaction_ref ?? 'INV-' . str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
            </td>
            <td class="meta-label">Issued To:</td>
            <td>{{ $order->user->name ?? 'Valued Customer' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Date Issued:</td>
            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
            <td class="meta-label">Email:</td>
            <td>{{ $order->user->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Payment Method:</td>
            <td>{{ $order->payment_method ?? 'Unknown' }}</td>
            <td class="meta-label">Status:</td>
            <td>
                @php
                    $statusLabel = $order->payment_status;
                    $badgeClass = 'status-pending';

                    if ($order->payment_status === 'paid' || $order->payment_status === 'approved') {
                        $statusLabel = 'PAID';
                        $badgeClass = 'status-paid';
                    } else {
                        $statusLabel = strtoupper($order->payment_status);
                    }
                @endphp
                <span class="status-badge {{ $badgeClass }}">
                    {{ $statusLabel }}
                </span>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Service / Product</th>
                <th>Description</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total (FRW)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->product_name }}</td>
                <td>Order #{{ $order->id }} - {{ $order->product_name }} Setup/Service</td>
                <td class="text-right">{{ $order->quantity }}</td>
                <td class="text-right">{{ number_format($order->price, 0) }}</td>
                <td class="text-right" style="font-weight: bold;">
                    {{ number_format($order->price * $order->quantity, 0) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-item" style="font-size: 14px; font-weight: normal; margin-bottom: 5px;">
            <span style="color: #64748b; margin-right: 15px;">Subtotal:</span>
            FRW {{ number_format(($order->price * $order->quantity) / 1.18, 0) }}
        </div>
        <div class="summary-item" style="font-size: 14px; font-weight: normal; margin-bottom: 5px;">
            <span style="color: #64748b; margin-right: 15px;">VAT (18%):</span>
            FRW {{ number_format(($order->price * $order->quantity) - (($order->price * $order->quantity) / 1.18), 0) }}
        </div>
        <div class="summary-item" style="border-top: 2px solid #e2e8f0; padding-top: 10px; margin-top: 10px;">
            <span style="color: #64748b; font-size: 12px; font-weight: normal; margin-right: 15px;">TOTAL AMOUNT
                PAID</span>
            FRW {{ number_format($order->price * $order->quantity, 0) }}
        </div>
    </div>

    <div class="footer">
        This document serves as proof of payment. Generated via eVuba Connect Platform. &copy; {{ date('Y') }} eVuba.
    </div>
</body>

</html>