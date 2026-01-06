@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <!-- Feedback Notifications -->
    @if(session('success'))
        <div class="glass-alert success" id="successAlert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="pro-header">
        <div>
            <h1>Order Hub & Marketplace</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Monitor incoming demand and manage your digital
                storefront</p>
        </div>
        <div class="header-actions" style="display: flex; gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 0.7rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                <input type="date" id="report_start" class="filter-input"
                    style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--dark);">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 0.7rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                <input type="date" id="report_end" class="filter-input"
                    style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--dark);">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="downloadReport('pdf')" class="action-btn" style="background: #ef4444; color: white;">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button onclick="downloadReport('excel')" class="action-btn" style="background: #22c55e; color: white;">
                    <i class="fas fa-file-excel"></i> EXCEL
                </button>
            </div>
        </div>
    </div>

    <!-- Order Intelligence Stats -->
    <div class="dashboard-grid" style="margin-bottom: 2rem;">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Orders</div>
                    <div class="value">{{ number_format($stats['total']) }}</div>
                    <div class="stat-trend up">All-time volume</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Pending Queue</div>
                    <div class="value">{{ number_format($stats['pending']) }}</div>
                    <div class="stat-trend {{ $stats['pending'] > 5 ? 'down' : 'up' }}">
                        {{ $stats['pending'] > 5 ? 'Action Required' : 'In Control' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Completed</div>
                    <div class="value">{{ number_format($stats['completed']) }}</div>
                    <div class="stat-trend up">Finalized Despatches</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Projected Revenue</div>
                    <div class="value">FRW {{ number_format($stats['revenue']) }}</div>
                    <div class="stat-trend up">Market Valuation</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Container: Vertical Stack for maximum width -->
    <div class="orchestration-stack" style="display: flex; flex-direction: column; gap: 2rem;">

        <!-- Top Section: Analytics & Global Controls -->
        <div class="analytics-row" style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem;">
            <div class="pro-card">
                <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-chart-line"
                        style="margin-right: 0.5rem; color: var(--primary);"></i> Market Performance Pulse</h3>
                <div style="height: 250px; width: 100%;">
                    <canvas id="orderPulseChart"></canvas>
                </div>
            </div>

            <!-- Quick Action / Summary Panel -->
            <div class="pro-card glass-card" style="background: rgba(99, 102, 241, 0.05);">
                <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-bullseye"
                        style="margin-right: 0.5rem; color: var(--primary);"></i> Operational Focus</h3>
                <div class="focus-item"
                    style="margin-bottom: 1rem; padding: 1rem; background: var(--white); border-radius: 1rem; border: 1px solid var(--glass-border);">
                    <div style="font-size: 0.75rem; color: var(--secondary);">Priority Processing</div>
                    <div style="font-weight: 700; font-size: 1.1rem; color: var(--warning);">{{ $stats['pending'] }}
                        Orders Waiting</div>
                </div>
                <div class="focus-item"
                    style="margin-bottom: 1rem; padding: 1rem; background: var(--white); border-radius: 1rem; border: 1px solid var(--glass-border);">
                    <div style="font-size: 0.75rem; color: var(--secondary);">Inventory Health</div>
                    <div style="font-weight: 700; font-size: 1.1rem; color: var(--success);">{{ count($products) }} SKUs
                        Live</div>
                </div>
                <p style="font-size: 0.8rem; color: var(--secondary); line-height: 1.5;">
                    <i class="fas fa-info-circle"></i> Use the Catalog Designer below to update product visibility or
                    add new market offerings. Orders marked as 'Processing' trigger client notifications.
                </p>
            </div>
        </div>

        <!-- Middle Section: Catalog Designer & Inventory -->
        <div class="marketplace-row" style="display: grid; grid-template-columns: 450px 1fr; gap: 2rem;">
            <!-- Catalog Designer -->
            <div class="pro-card">
                <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-magic" style="color: var(--primary);"></i>
                    <span id="form-title">Catalog Designer</span>
                </h3>
                <form id="product-form" action="{{ route('manager.products.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id">

                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" id="name" placeholder="e.g. Premium Leather Jacket" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>SKU Code</label>
                            <input type="text" name="product_code" id="product_code" placeholder="SKU-XXXX">
                        </div>
                        <div class="form-group">
                            <label>Classification</label>
                            <select name="category_id" id="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Story/Description</label>
                        <textarea name="description" id="description" rows="2"
                            placeholder="Tell the product's story..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Brand Imagery</label>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <input type="file" name="image" id="image" onchange="previewImage(this)"
                                style="font-size: 0.8rem; flex: 1;">
                            <div id="image-preview"
                                style="display: none; width: 60px; height: 60px; border-radius: 0.5rem; overflow: hidden; border: 1px solid var(--glass-border);">
                                <img src="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                        <button type="submit" name="action" value="create" class="action-btn btn-primary"
                            id="create-btn" style="flex: 1;">
                            <i class="fas fa-plus-circle"></i> DRAFT
                        </button>
                        <button type="submit" name="action" value="publish" class="action-btn"
                            style="flex: 1; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">
                            <i class="fas fa-globe"></i> PUBLISH
                        </button>
                        <button type="submit" name="action" value="update" class="action-btn btn-primary"
                            id="update-btn" style="display:none; flex: 1;">
                            <i class="fas fa-sync-alt"></i> SYNC
                        </button>
                    </div>
                </form>
            </div>

            <!-- Inventory Grid -->
            <div class="pro-card">
                <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-boxes"
                        style="margin-right: 0.5rem; color: var(--primary);"></i> Collection Inventory</h3>
                <div class="inventory-scroll"
                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; max-height: 380px; overflow-y: auto; padding-right: 0.5rem;">
                    @foreach($products as $product)
                        <div class="pro-card inventory-item" onclick="loadProductForEdit({{ json_encode($product) }})"
                            style="padding: 0.75rem; cursor: pointer; border: 1px solid var(--glass-border); transition: all 0.2s; background: var(--bg-main);">
                            <div
                                style="width: 100%; height: 100px; background: var(--white); border-radius: 0.5rem; overflow: hidden; margin-bottom: 0.75rem;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div
                                        style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--secondary);">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div
                                style="font-weight: 700; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $product->name }}
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                <span
                                    class="status-badge {{ $product->status == 'published' ? 'status-processing' : 'status-cancelled' }}"
                                    style="font-size: 0.65rem;">
                                    {{ strtoupper($product->status ?? 'DRAFT') }}
                                </span>
                                <form method="POST" action="{{ route('manager.products.toggleStatus', $product->id) }}"
                                    style="display: inline;" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit" class="action-btn"
                                        style="padding: 2px 6px; font-size: 0.75rem; background: var(--white); color: var(--primary); border: 1px solid var(--glass-border);">
                                        <i
                                            class="fas {{ $product->status == 'published' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bottom Section: Orders Ledger (Full Width) -->
        <div class="orders-row">
            <div class="pro-card" style="padding: 0; overflow: hidden;">
                <div
                    style="padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                    <h3 style="margin:0;"><i class="fas fa-receipt"
                            style="margin-right: 0.5rem; color: var(--primary);"></i> Orders Ledger</h3>

                    <form action="{{ route('manager.orders.index') }}" method="GET" class="ledger-controls"
                        style="display: flex; gap: 1rem; flex: 1; max-width: 600px;">
                        <div class="pro-search"
                            style="flex: 1; background: var(--bg-main); padding: 0.5rem 1rem; border-radius: 0.75rem; display: flex; align-items: center;">
                            <i class="fas fa-search" style="color: var(--secondary); margin-right: 0.75rem;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search client, product, or reference..."
                                style="background: none; border: none; width: 100%; outline: none; font-size: 0.9rem;">
                            <input type="hidden" name="start_date" id="form_start_date">
                            <input type="hidden" name="end_date" id="form_end_date">
                        </div>
                        <button type="submit" class="action-btn" style="background: var(--bg-main); color: var(--secondary); border: 1px solid var(--glass-border);">
                            <i class="fas fa-sync-alt"></i> REFRESH
                        </button>
                    </form>
                </div>

                <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                    <table class="pro-table" style="width: 100%; min-width: 1100px;">
                        <thead
                            style="position: sticky; top: 0; z-index: 10; background: var(--white); box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                            <tr>
                                <th>REF ID</th>
                                <th>Client Identity</th>
                                <th>Product Details</th>
                                <th>Volume</th>
                                <th>Valuation (FRW)</th>
                                <th>Status Cycle</th>
                                <th>Timeline</th>
                                <th style="text-align: right;">Orchestrate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr style="transition: background 0.2s;">
                                    <td><span style="font-weight: 700; color: var(--primary);">#{{ $order->id }}</span></td>
                                    <td>
                                        <div style="font-weight: 700;">{{ $order->user->name ?? 'Market Buyer' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--secondary);">
                                            {{ $order->user->email ?? 'no-digital-signature' }}
                                        </div>
                                    </td>
                                    <td style="font-weight: 600;">{{ $order->product_name }}</td>
                                    <td><span
                                            style="font-weight: 800; color: var(--primary); background: rgba(99, 102, 241, 0.1); padding: 0.25rem 0.5rem; border-radius: 0.4rem;">{{ $order->quantity }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 800; font-size: 1rem;">
                                            {{ number_format($order->quantity * $order->price) }}
                                        </div>
                                        <div style="font-size: 0.7rem; color: var(--secondary);">UNIT:
                                            {{ number_format($order->price) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 600; font-size: 0.85rem;">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div
                                            style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center;">
                                            <form method="POST" action="{{ route('manager.orders.update', $order->id) }}">
                                                @csrf @method('PUT')
                                                <select name="status" onchange="this.form.submit()" class="glass-select"
                                                    style="padding: 0.4rem 0.6rem; font-size: 0.75rem; font-weight: 600;">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                        PENDING</option>
                                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                                    <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>
                                                        APPROVED</option>
                                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>COMPLETED</option>
                                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                                                </select>
                                            </form>
                                            <form method="POST" action="{{ route('manager.orders.destroy', $order->id) }}"
                                                onsubmit="return confirm('Archive this order record?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-btn"
                                                    style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.1);">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 6rem; color: var(--secondary);">
                                        <i class="fas fa-layer-group"
                                            style="font-size: 4rem; opacity: 0.05; margin-bottom: 1.5rem; display: block;"></i>
                                        <h3 style="opacity: 0.5;">Empty Ledger</h3>
                                        <p>No active demand cycles recorded in the system.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper"
                    style="padding: 1.5rem; border-top: 1px solid var(--glass-border); display: flex; justify-content: center;">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        border-radius: 0.75rem;
        font-size: 0.9rem;
        color: var(--dark);
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        background: var(--white);
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .inventory-item:hover {
        border-color: var(--primary) !important;
        transform: translateY(-3px);
        box-shadow: var(--card-shadow);
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .status-badge.status-pending {
        background: rgba(148, 163, 184, 0.1);
        color: #64748b;
    }

    .status-badge.status-processing {
        background: rgba(56, 189, 248, 0.1);
        color: #0ea5e9;
    }

    .status-badge.status-approved {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .status-badge.status-completed {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .status-badge.status-cancelled {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .glass-alert {
        padding: 1.25rem 1.75rem;
        border-radius: 1rem;
        backdrop-filter: blur(15px);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border: 1px solid var(--glass-border);
        box-shadow: var(--card-shadow);
        animation: slideDown 0.5s ease;
    }

    .glass-alert.success {
        background: rgba(34, 197, 94, 0.1);
        color: #15803d;
    }

    .glass-select {
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        border-radius: 0.6rem;
        color: var(--dark);
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .glass-select:hover {
        border-color: var(--primary);
    }

    @keyframes slideDown {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Custom Scrollbar for Inventory & Table */
    .inventory-scroll::-webkit-scrollbar,
    .table-responsive::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    .inventory-scroll::-webkit-scrollbar-track,
    .table-responsive::-webkit-scrollbar-track {
        background: transparent;
    }

    .inventory-scroll::-webkit-scrollbar-thumb,
    .table-responsive::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .inventory-scroll::-webkit-scrollbar-thumb:hover,
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    /* Pagination Styling */
    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: 1px solid var(--glass-border);
        background: var(--bg-main);
        color: var(--dark);
    }

    .pagination a:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .pagination .active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }

    .pagination .disabled span {
        opacity: 0.4;
        cursor: not-allowed;
        background: var(--bg-main);
    }

    /* Hide default laravel/bootstrap text summary "Showing x to y of z results" if it breaks layout */
    .pagination-wrapper nav>div.d-none.flex-sm-fill {
        display: none !important;
    }

    .pagination-wrapper nav>div:first-child {
        display: none !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const img = preview.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function loadProductForEdit(product) {
        document.getElementById('product_id').value = product.id;
        document.getElementById('name').value = product.name;
        document.getElementById('description').value = product.description || '';
        document.getElementById('product_code').value = product.product_code || '';
        document.getElementById('category_id').value = product.category_id || '';

        const imgPreview = document.getElementById('image-preview');
        const img = imgPreview.querySelector('img');
        if (product.image) {
            img.src = `{{ asset('storage') }}/${product.image}`;
            imgPreview.style.display = 'block';
        } else {
            imgPreview.style.display = 'none';
        }

        document.getElementById('create-btn').style.display = 'none';
        document.getElementById('update-btn').style.display = 'inline-block';
        document.getElementById('form-title').innerText = 'Market SKU Editor';
        document.getElementById('product-form').action = `/manager/products/update/${product.id}`;

        document.getElementById('product-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    const ctx = document.getElementById('orderPulseChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.date),
            datasets: [{
                label: 'Market Revenue (FRW)',
                data: chartData.map(d => d.revenue),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.08)',
                fill: true,
                tension: 0.45,
                pointRadius: 6,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function (context) {
                            return 'FRW ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.03)' },
                    ticks: {
                        font: { size: 11, weight: '600' },
                        callback: function (value) { return 'FRW ' + (value / 1000) + 'k'; }
                    }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } }
            }
        }
    });

    function downloadReport(type) {
        const start = document.getElementById('report_start').value;
        const end = document.getElementById('report_end').value;
        if (!start || !end) {
            alert('Please select both start and end date for revenue audit.');
            return;
        }
        const baseUrl = type === 'pdf' ? "{{ route('manager.orders.export.pdf') }}" : "{{ route('manager.orders.export.excel') }}";
        window.location.href = `${baseUrl}?start_date=${start}&end_date=${end}`;
    }

    setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.style.display = 'none', 500);
        }
    }, 4000);

    // Sync dates on form submit
    document.querySelector('.ledger-controls').addEventListener('submit', function() {
        document.getElementById('form_start_date').value = document.getElementById('report_start').value;
        document.getElementById('form_end_date').value = document.getElementById('report_end').value;
    });
</script>