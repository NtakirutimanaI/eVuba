<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', 'Helvetica', sans-serif;
        }

        body {
            background: #ffffff;
            padding: 40px 30px;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #4f46e5;
            border-radius: 10px;
            padding: 30px;
        }

        /* Header */
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
        }

        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .company-logo {
            font-size: 32px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.8;
        }

        .invoice-meta {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .invoice-title {
            font-size: 36px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .invoice-date {
            font-size: 11px;
            color: #666;
        }

        /* Bill To Section */
        .bill-to-section {
            margin-bottom: 30px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }

        .bill-to-title {
            font-size: 14px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        .customer-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .customer-details {
            font-size: 11px;
            color: #666;
            line-height: 1.8;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background: #4f46e5;
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .items-table td {
            padding: 12px;
            font-size: 11px;
            color: #333;
        }

        .items-table td.text-right {
            text-align: right;
        }

        .items-table td.text-center {
            text-align: center;
        }

        /* Summary Section */
        .summary-section {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }

        .summary-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-label {
            display: table-cell;
            font-size: 12px;
            color: #666;
        }

        .summary-value {
            display: table-cell;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        .summary-total {
            background: #4f46e5;
            color: white;
            padding: 12px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .summary-total .summary-label,
        .summary-total .summary-value {
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        /* Payment Info */
        .payment-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
        }

        .payment-title {
            font-size: 12px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 8px;
        }

        .payment-details {
            font-size: 11px;
            color: #666;
        }

        /* Notes */
        .notes-section {
            margin-bottom: 30px;
        }

        .notes-title {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .notes-content {
            font-size: 11px;
            color: #666;
            line-height: 1.8;
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
        }

        /* Footer */
        .invoice-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            font-size: 10px;
            color: #999;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">eVubaConnect</div>
                <div class="company-details">
                    Stock Management System<br>
                    Kigali, Rwanda<br>
                    Phone: +250 XXX XXX XXX<br>
                    Email: info@evubaconnect.com
                </div>
            </div>
            <div class="invoice-meta">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                <div class="invoice-date">
                    Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}<br>
                    <span class="status-badge status-{{ strtolower($invoice->status) }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Bill To -->
        <div class="bill-to-section">
            <div class="bill-to-title">BILL TO:</div>
            <div class="customer-name">{{ $invoice->customer_name }}</div>
            <div class="customer-details">
                @if($invoice->customer)
                    @if($invoice->customer->email)
                        Email: {{ $invoice->customer->email }}<br>
                    @endif
                    @if($invoice->customer->phone)
                        Phone: {{ $invoice->customer->phone }}<br>
                    @endif
                    @if($invoice->customer->address)
                        Address: {{ $invoice->customer->address }}
                    @endif
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">Description</th>
                    <th width="15%" class="text-center">Quantity</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">FRW {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">FRW {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-row">
                <div class="summary-label">Subtotal:</div>
                <div class="summary-value">FRW {{ number_format($invoice->subtotal, 2) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Tax (18%):</div>
                <div class="summary-value">FRW {{ number_format($invoice->tax_amount, 2) }}</div>
            </div>
            <div class="summary-total">
                <div class="summary-row" style="border: none; padding: 0;">
                    <div class="summary-label">TOTAL AMOUNT:</div>
                    <div class="summary-value">FRW {{ number_format($invoice->grand_total, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="payment-title">PAYMENT INFORMATION</div>
            <div class="payment-details">
                Payment Method: <strong>{{ ucwords(str_replace('_', ' ', $invoice->payment_method)) }}</strong><br>
                Status: <strong>{{ ucfirst($invoice->status) }}</strong>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->description)
        <div class="notes-section">
            <div class="notes-title">NOTES:</div>
            <div class="notes-content">{{ $invoice->description }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="thank-you">Thank you for your business!</div>
            <div>
                This is a computer-generated invoice and is valid without signature.<br>
                eVubaConnect - Digital Community & Stock Management Platform<br>
                © {{ date('Y') }} VUBA TECH Ltd. All Rights Reserved
            </div>
        </div>

    </div>
</body>
</html>
