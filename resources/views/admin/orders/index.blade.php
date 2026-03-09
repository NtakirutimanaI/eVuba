@include('layouts.header')
@include('layouts.sidebar')


<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<style>
    /* Theme Variables */
    /* Rely on global variables from app layout */

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--body-bg);
        font-family: 'Inter', sans-serif;
        color: var(--text-primary);
        margin: 0;
        min-height: 100vh;
        overflow-x: hidden; /* Prevent horizontal scroll glitch */
    }

    .main-content {
        margin-left: 200px; /* match sidebar width exactly */
        width: calc(100% - 200px);
        margin-top: 64px; /* match header height */
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    /* Modal Styling */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1100;
    }

    .modal-content {
        background: var(--surface);
        width: 90%;
        max-width: 1000px;
        max-height: 80vh;
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--header-border);
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 20px 30px;
        background: var(--surface);
        border-bottom: 1px solid var(--header-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modal-body {
        padding: 30px;
        overflow-y: auto;
        flex: 1;
    }

    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: var(--header-border);
        border-radius: 10px;
    }

    .close-modal {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--body-bg);
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s;
    }

    .close-modal:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
        margin-top: 25px;
    }

    .glass-card {
        background: var(--surface);
        border: 1px solid var(--header-border);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--header-border);
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.2s;
        background: var(--body-bg);
        color: var(--text-primary);
    }

    .form-control:focus {
        border-color: var(--primary);
        background: var(--surface);
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .btn-primary {
        padding: 10px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .product-manager-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 25px;
    }

    .product-list-mini {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }

    .product-item {
        background: var(--surface);
        border: 1px solid var(--header-border);
        border-radius: 12px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .product-item:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.08);
    }

    .product-img {
        width: 100%;
        height: 110px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 10px;
        background: var(--body-bg);
    }

    .product-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .product-meta {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--header-border);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-content h4 {
        margin: 0;
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
    }

    .stat-content .value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin-top: 4px;
    }

    /* Table */
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* smooth scroll on iOS */
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        background: var(--body-bg);
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        border-bottom: 1px solid var(--header-border);
    }

    .modern-table td {
        padding: 16px;
        font-size: 13px;
        border-bottom: 1px solid var(--body-bg);
        color: var(--text-primary);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-processing {
        background: rgba(59, 130, 246, 0.1);
        color: #0284c7;
    }

    .status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #16a34a;
    }

    .status-cancelled {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    /* Dark mode adjustments for badges text */
    @media (prefers-color-scheme: dark) {
        .status-pending {
            color: #fbbf24;
        }

        .status-processing {
            color: #60a5fa;
        }

        .status-completed {
            color: #34d399;
        }

        .status-cancelled {
            color: #f87171;
        }

        .status-approved {
            color: #34d399;
        }
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav {
        display: flex;
        gap: 5px;
    }

    .pagination-wrapper .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination-wrapper .page-link {
        padding: 8px 14px;
        border-radius: 8px;
        background: var(--surface);
        border: 1px solid var(--header-border);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination-wrapper .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    @media (max-width: 1024px) {
        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 15px;
        }

        .product-manager-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">

    @php
        $totalRevenue = $totalStats['total_revenue'] ?? 0;
        $pendingCount = $totalStats['pending_count'] ?? 0;
        $totalOrders = $totalStats['total_orders'] ?? 0;
    @endphp

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;"><i
                    class="fas fa-shopping-cart"></i></div>
            <div class="stat-content">
                <h4>Total Orders</h4>
                <div class="value">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i
                    class="fas fa-money-bill-wave"></i></div>
            <div class="stat-content">
                <h4>Total Revenue</h4>
                <div class="value">{{ number_format($totalRevenue) }} Rwf</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i
                    class="fas fa-clock"></i></div>
            <div class="stat-content">
                <h4>Pending</h4>
                <div class="value">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div
            style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 500; border: 1px solid rgba(16, 185, 129, 0.2);">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-grid">
        <div class="glass-card">
            <div class="section-header">
                <div class="section-title"><i class="fas fa-list-alt" style="color: var(--primary);"></i> Recent Orders
                </div>
                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <button class="btn-primary" onclick="openProductModal()" style="background: var(--info);">
                        <i class="fas fa-boxes"></i> Manage Products
                    </button>

                    <form method="GET" action="{{ route('admin.orders.index') }}" style="margin-left: 5px;">
                        <select name="per_page" onchange="this.form.submit()"
                            style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--header-border); font-size: 12px; background: var(--surface); color: var(--text-primary); cursor: pointer; font-weight: 600;">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Rows</option>
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Rows</option>
                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 Rows</option>
                        </select>
                        <!-- Persist other filters if they exist -->
                        @if(request('start_date')) <input type="hidden" name="start_date"
                        value="{{ request('start_date') }}"> @endif
                        @if(request('end_date')) <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        @endif
                    </form>

                    <div
                        style="display: flex; align-items: center; gap: 8px; background: var(--surface); padding: 5px 12px; border-radius: 8px; border: 1px solid var(--header-border); margin-left: auto;">
                        <label style="font-size: 11px; font-weight: 600; color: var(--text-muted);">From:</label>
                        <input type="date" id="report_start" class="form-control"
                            style="padding: 4px; font-size: 11px; width: 120px; height: auto;">
                        <label style="font-size: 11px; font-weight: 600; color: var(--text-muted);">To:</label>
                        <input type="date" id="report_end" class="form-control"
                            style="padding: 4px; font-size: 11px; width: 120px; height: auto;">

                        <div class="export-group"
                            style="display: flex; gap: 8px; margin-left: 10px; border-left: 1px solid var(--header-border); padding-left: 15px;">
                            <button type="button" onclick="generateReport('pdf')" class="btn-primary"
                                style="background: #ef4444; padding: 8px 15px; font-size: 12px;">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button type="button" onclick="generateReport('excel')" class="btn-primary"
                                style="background: #10b981; padding: 8px 15px; font-size: 12px;">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        style="color: var(--primary); font-weight: bold; text-decoration: none;">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 32px; height: 32px; border-radius: 50%; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px;">
                                            {{ substr($order->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text-primary);">
                                                {{ $order->user->name ?? 'Guest' }}
                                            </div>
                                            <div style="font-size: 11px; color: var(--text-muted);">
                                                {{ $order->user->email ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        style="font-weight: 500; color: var(--text-primary);">{{ $order->product_name }}</span><br>
                                    <span style="font-size: 11px; color: var(--text-muted);">Qty:
                                        {{ $order->quantity }}</span>
                                </td>
                                <td style="font-weight: 700; color: var(--text-primary);">
                                    {{ number_format($order->quantity * $order->price) }} Rwf
                                    @if($order->payment_method)
                                        <div
                                            style="font-size: 10px; font-weight: normal; margin-top: 4px; color: var(--text-muted);">
                                            <i class="fas fa-credit-card"></i> {{ $order->payment_method }}
                                        </div>
                                        <div
                                            style="font-size: 10px; font-weight: bold; margin-top: 2px; color: {{ $order->payment_status == 'approved' ? 'var(--success)' : ($order->payment_status == 'pending_approval' ? 'var(--warning)' : ($order->payment_status == 'paid' ? 'var(--success)' : 'var(--text-muted)')) }};">
                                            {{ $order->payment_status == 'approved' ? 'Paid' : ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                        </div>
                                    @endif
                                </td>
                                <td><span class="status-badge status-{{ $order->status }}">
                                        {{ $order->status == 'approved' ? 'Paid' : ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        {{-- Payment Actions --}}
                                        @if($order->payment_status == 'pending_approval')
                                            <form method="POST" action="{{ route('admin.orders.approve_payment', $order->id) }}"
                                                title="Approve Payment">
                                                @csrf
                                                <button type="submit" class="action-btn"
                                                    style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.orders.download_invoice', $order->id) }}"
                                            class="action-btn" title="Download Invoice"
                                            style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; text-decoration: none;">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <form method="POST" action="{{ route('admin.orders.send_invoice', $order->id) }}"
                                            title="Send Invoice">
                                            @csrf
                                            <button type="submit" class="action-btn"
                                                style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                                            @csrf @method('PUT')
                                            <select name="status" onchange="this.form.submit()"
                                                style="padding: 4px; border-radius: 6px; border: 1px solid var(--header-border); font-size: 11px; background: var(--body-bg); color: var(--text-primary); width: 80px;">
                                                @foreach(['pending', 'processing', 'approved'] as $st)
                                                    <option value="{{ $st }}" {{ $order->status == $st ? 'selected' : '' }}>
                                                        {{ $st == 'approved' ? 'Paid' : ucfirst($st) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}"
                                            onsubmit="return confirm('Delete order?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination-wrapper">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- PRODUCT MANAGEMENT MODAL -->
<div id="productModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="section-title" style="margin: 0; font-size: 20px;">
                <i class="fas fa-boxes" style="color: var(--primary);"></i> Product Management
            </div>
            <span class="close-modal" onclick="closeProductModal()"><i class="fas fa-times"></i></span>
        </div>

        <div class="modal-body">
            <div class="product-manager-layout">
                <!-- Form Side -->
                <div>
                    <div class="glass-card"
                        style="background: var(--surface); border-radius: 12px; padding: 20px; border: 1px solid var(--header-border);">
                        <h3 id="form-title"
                            style="margin-top: 0; font-size: 15px; color: var(--text-primary); border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 4px; margin-bottom: 20px;">
                            Create New Product</h3>
                        <form id="product-form" action="{{ route('admin.products.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" id="product_id">

                            <div class="form-group">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter name..." required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Code (Optional)</label>
                                <input type="text" name="product_code" id="product_code" class="form-control"
                                    placeholder="SKU...">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="2"
                                    placeholder="Brief details..."></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>

                            <div style="display: flex; gap: 8px; margin-top: 15px;">
                                <button type="submit" name="action" value="create" class="btn-primary" id="create-btn"
                                    style="flex: 2;">
                                    <i class="fas fa-plus"></i> Create
                                </button>
                                <button type="submit" name="action" value="update" class="btn-primary" id="update-btn"
                                    style="display:none; flex: 2; background: var(--info);">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <button type="button" id="cancel-btn" class="btn-primary"
                                    style="display:none; flex: 1; background: var(--secondary);" onclick="resetForm()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List Side -->
                <div>
                    <div class="section-title"
                        style="font-size: 14px; margin-bottom: 15px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-th-list"></i> CATALOG OVERVIEW
                    </div>
                    <div class="product-list-mini">
                        @foreach($products as $product)
                            <div class="product-item" onclick="editProduct(this)" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" data-description="{{ $product->description }}"
                                data-code="{{ $product->product_code }}" data-category="{{ $product->category_id }}">

                                <div style="position: relative;">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($product->name) }}"
                                        class="product-img">
                                    <span
                                        style="position: absolute; top: 10px; right: 10px; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; background: {{ $product->status === 'published' ? 'var(--success)' : 'var(--secondary)' }}; color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                        {{ $product->status }}
                                    </span>
                                </div>

                                <div class="product-name">{{ $product->name }}</div>

                                <div class="product-meta">
                                    <span title="Category"><i class="fas fa-folder-open"
                                            style="margin-right: 4px; opacity: 0.7;"></i>
                                        {{ $product->category->name ?? 'Uncategorized' }}</span>
                                </div>

                                <div
                                    style="display: flex; gap: 10px; margin-top: 15px; border-top: 1px solid var(--header-border); padding-top: 12px; align-items: center;">
                                    <form method="POST" action="{{ route('admin.products.toggleStatus', $product->id) }}"
                                        style="flex: 1;">
                                        @csrf
                                        <button type="submit"
                                            style="width: 100%; font-size: 11px; font-weight: 600; padding: 6px; border-radius: 8px; border: 1px solid var(--header-border); background: var(--body-bg); cursor: pointer; color: var(--text-muted); transition: all 0.2s;"
                                            onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
                                            onmouseout="this.style.borderColor='var(--header-border)'; this.style.color='var(--text-muted)'">
                                            <i class="fas {{ $product->status == 'published' ? 'fa-eye-slash' : 'fa-eye' }}"
                                                style="margin-right: 4px;"></i>
                                            {{ $product->status == 'published' ? 'Hide' : 'Publish' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                                        onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="color: var(--text-muted); border: none; background: none; font-size: 16px; cursor: pointer; transition: color 0.2s;"
                                            onmouseover="this.style.color='var(--danger)'"
                                            onmouseout="this.style.color='var(--text-muted)'"
                                            onclick="event.stopPropagation()">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- product-list-mini -->
                </div> <!-- List Side -->
            </div> <!-- product-manager-layout -->
        </div> <!-- modal-body -->
    </div> <!-- modal-content -->
</div> <!-- modal-overlay -->

<script>
    function openProductModal() {
        document.getElementById('productModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        document.getElementById('productModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function editProduct(card) {
        const productId = card.dataset.id;
        document.getElementById('product_id').value = productId;
        document.getElementById('name').value = card.dataset.name;
        document.getElementById('description').value = card.dataset.description;
        document.getElementById('product_code').value = card.dataset.code;
        document.getElementById('category_id').value = card.dataset.category;

        document.getElementById('create-btn').style.display = 'none';
        document.getElementById('update-btn').style.display = 'flex';
        document.getElementById('cancel-btn').style.display = 'inline-block';
        document.getElementById('form-title').innerText = 'Update Product: ' + card.dataset.name;
        document.getElementById('product-form').action = `/admin/products/update/${productId}`;
    }

    function resetForm() {
        document.getElementById('product-form').reset();
        document.getElementById('product_id').value = '';
        document.getElementById('create-btn').style.display = 'flex';
        document.getElementById('update-btn').style.display = 'none';
        document.getElementById('cancel-btn').style.display = 'none';
        document.getElementById('form-title').innerText = 'Create New Product';
        document.getElementById('product-form').action = "{{ route('admin.products.store') }}";
    }

    function generateReport(type) {
        const start = document.getElementById('report_start').value;
        const end = document.getElementById('report_end').value;

        if (!start || !end) {
            alert('Please select both Start and End dates to generate the report.');
            return;
        }

        let url = type === 'pdf'
            ? "{{ route('admin.orders.report.pdf') }}"
            : "{{ route('admin.orders.report.excel') }}";

        const params = new URLSearchParams();
        params.append('start_date', start);
        params.append('end_date', end);

        window.open(url + '?' + params.toString(), '_blank');
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        if (event.target == document.getElementById('productModal')) {
            closeProductModal();
        }
    }
</script>