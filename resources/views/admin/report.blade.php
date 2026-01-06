@include('layouts.header')
@include('layouts.sidebar')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-color: var(--body-bg);
        font-family: 'Inter', sans-serif;
        color: var(--text-primary);
    }

    .report-wrapper {
        margin-left: 242px;
        /* Sidebar (222px) + Gap (20px) */
        padding: 30px;
        transition: all 0.3s ease;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .report-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
    }

    .grid-4 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--surface);
        backdrop-filter: blur(10px);
        border: 1px solid var(--header-border);
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: white;
    }

    .stat-content .label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-content .value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-primary);
        margin-top: 4px;
    }

    /* Report Modules Section */
    .module-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
    }

    .module-card {
        background: var(--surface);
        border-radius: 20px;
        border: 1px solid var(--header-border);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08);
    }

    .module-header {
        padding: 20px;
        background: var(--header-bg);
        border-bottom: 1px solid var(--header-border);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .module-header i {
        font-size: 18px;
        color: var(--primary);
    }

    .module-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .module-body {
        padding: 20px;
    }

    .module-body p {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .btn-group {
        display: flex;
        gap: 10px;
    }

    .report-btn {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* High Specificity Overrides for Dark Mode */
    html[data-theme="dark"] .report-wrapper .module-grid .module-card .module-body .btn-group .report-btn.pdf-btn {
        background: #fee2e2 !important;
        color: #dc2626 !important;
    }

    html[data-theme="dark"] .report-wrapper .module-grid .module-card .module-body .btn-group .report-btn.excel-btn {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    html[data-theme="dark"] .report-wrapper .module-grid .module-card .module-body .btn-group .report-btn.view-btn {
        background: #e0e7ff !important;
        color: #4f46e5 !important;
    }

    /* Base Styles */
    .pdf-btn {
        background: #fee2e2;
        color: #dc2626;
    }

    .excel-btn {
        background: #dcfce7;
        color: #16a34a;
    }

    .view-btn {
        background: #e0e7ff;
        color: #4f46e5;
    }

    @media (max-width: 1024px) {
        .report-wrapper {
            margin-left: 0;
            padding: 20px;
        }
    }
</style>

<div class="report-wrapper">
    <div class="report-header">
        <div>
            <h1>Intelligence Reports</h1>
            <p style="color: var(--text-muted); margin-top: 5px;">Generate and download comprehensive system reports.
            </p>
        </div>
        <div
            style="font-size: 14px; font-weight: 600; background: var(--surface); color: var(--text-primary); padding: 10px 20px; border-radius: 12px; border: 1px solid var(--header-border);">
            <i class="far fa-calendar-alt" style="margin-right: 8px;"></i> {{ date('F d, Y') }}
        </div>
    </div>

    {{-- Quick stats summary --}}
    <div class="grid-4">
        <div class="stat-card">
            <div class="icon-box" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <div class="label">Revenue</div>
                <div class="value">{{ number_format($stats['total_sales']) }} RWF</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: linear-gradient(135deg, #10b981, #34d399);">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-content">
                <div class="label">Orders</div>
                <div class="value">{{ number_format($stats['total_orders']) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stat-content">
                <div class="label">Customers</div>
                <div class="value">{{ number_format($stats['total_customers']) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="label">Low Stock</div>
                <div class="value">{{ number_format($stats['low_stock']) }} Items</div>
            </div>
        </div>
    </div>

    {{-- Report Modules --}}
    <div class="module-grid">
        <!-- Sales & Finance -->
        <div class="module-card">
            <div class="module-header">
                <i class="fas fa-chart-line"></i>
                <h3>Sales & Financials</h3>
            </div>
            <div class="module-body">
                <p>Detailed analysis of revenue, sales trends, and payment statuses across all products and services.
                </p>
                <div class="btn-group">
                    <a href="{{ route('admin.orders.index') }}" class="report-btn view-btn"><i class="far fa-eye"></i>
                        View</a>
                    <a href="#" class="report-btn pdf-btn"><i class="far fa-file-pdf"></i> PDF</a>
                    <a href="#" class="report-btn excel-btn"><i class="far fa-file-excel"></i> Excel</a>
                </div>
            </div>
        </div>

        <!-- Inventory Report -->
        <div class="module-card">
            <div class="module-header">
                <i class="fas fa-boxes"></i>
                <h3>Inventory & Stock</h3>
            </div>
            <div class="module-body">
                <p>Monitor stock levels, track low inventory, and audit stock-in/stock-out transactions.</p>
                <div class="btn-group">
                    <a href="{{ route('admin.stock_in.index') }}" class="report-btn view-btn"><i class="far fa-eye"></i>
                        View</a>
                    <a href="#" class="report-btn pdf-btn"><i class="far fa-file-pdf"></i> PDF</a>
                </div>
            </div>
        </div>

        <!-- User/Customer Report -->
        <div class="module-card">
            <div class="module-header">
                <i class="fas fa-users-cog"></i>
                <h3>User & Audience</h3>
            </div>
            <div class="module-body">
                <p>Overview of customer growth, subscriber engagement, and employee performance metrics.</p>
                <div class="btn-group">
                    <a href="{{ route('admin.users.report') }}" class="report-btn view-btn"><i class="far fa-eye"></i>
                        View</a>
                    <a href="{{ route('admin.subscribers.export.pdf') }}" class="report-btn pdf-btn"><i
                            class="far fa-file-pdf"></i> PDF</a>
                    <a href="{{ route('admin.subscribers.export.excel') }}" class="report-btn excel-btn"><i
                            class="far fa-file-excel"></i> Excel</a>
                </div>
            </div>
        </div>

        <!-- Support Metrics -->
        <div class="module-card">
            <div class="module-header">
                <i class="fas fa-headset"></i>
                <h3>Support & Feedback</h3>
            </div>
            <div class="module-body">
                <p>Analytics on support ticket resolution times, customer satisfaction, and active inquiries.</p>
                <div class="btn-group">
                    <a href="{{ route('admin.support.index') }}" class="report-btn view-btn"><i class="far fa-eye"></i>
                        View</a>
                    <a href="#" class="report-btn pdf-btn"><i class="far fa-file-pdf"></i> PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>