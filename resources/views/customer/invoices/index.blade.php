@include('layouts.header')
@include('layouts.sidebar')

<div class="dashboard-wrapper">
    <div class="dashboard-header">
        <div class="header-left">
            <h1><i class="fas fa-file-invoice-dollar"></i> My Invoices</h1>
            <p>Access and download your official purchase documents.</p>
        </div>
        <div class="header-actions">
            <!-- Optional Filters Could Go Here -->
        </div>
    </div>

    <div class="recent-orders-card glass">
        <div class="card-header">
            <h3>Transaction History</h3>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Invoice Ref</th>
                        <th>Product / Service</th>
                        <th>Date Issued</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td style="font-family: monospace; font-weight: 700; color: var(--primary);">
                                #{{ $invoice->transaction_ref ?? 'INV-' . str_pad($invoice->id, 8, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $invoice->product->name ?? $invoice->product_name }}</div>
                            </td>
                            <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                            <td style="font-weight: 700;">{{ number_format($invoice->price * $invoice->quantity) }} RWF</td>
                            <td>
                                @php
                                    $statusClass = match ($invoice->payment_status) {
                                        'paid', 'approved' => 'status-paid',
                                        default => 'status-pending'
                                    };

                                    $statusLabel = match ($invoice->payment_status) {
                                        'paid', 'approved' => 'PAID',
                                        default => strtoupper($invoice->payment_status)
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('customer.orders.invoice', $invoice->id) }}" class="btn-icon"
                                    title="Download PDF"
                                    style="display: inline-flex; justify-content: center; align-items: center; text-decoration: none; color: var(--primary);">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-file-invoice"
                                    style="font-size: 40px; color: var(--text-muted); margin-bottom: 15px;"></i>
                                <p>No generated invoices found.</p>
                                <a href="{{ route('customer.orders.index') }}" class="glass-btn primary"
                                    style="margin-top: 10px;">Go to Marketplace</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $invoices->links() }}
        </div>
    </div>
</div>

<style>
    /* Reusing Dashboard Styles */
    .dashboard-wrapper {
        padding: 30px;
        margin-left: 250px;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-left h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-left h1 i {
        color: var(--primary);
    }

    .header-left p {
        color: var(--text-muted);
        font-size: 14px;
    }

    .glass {
        background: var(--surface);
        border: 1px solid var(--header-border);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    .recent-orders-card {
        padding: 0;
        overflow: hidden;
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--header-border);
    }

    .card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        text-align: left;
        padding: 15px 25px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--body-bg);
        border-bottom: 1px solid var(--header-border);
    }

    .modern-table td {
        padding: 15px 25px;
        font-size: 14px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--header-border);
        vertical-align: middle;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-progress {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--body-bg);
        border: 1px solid var(--header-border);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        border-color: var(--primary);
        background: var(--primary);
        color: white !important;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .pagination-container {
        padding: 20px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 1024px) {
        .dashboard-wrapper {
            margin-left: 0;
            padding: 20px;
        }
    }
</style>