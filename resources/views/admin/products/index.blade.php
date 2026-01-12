@include('layouts.header')
@include('layouts.sidebar')

<div class="products-wrapper">
    <!-- Notifications -->
    @if(session('success'))
        <div class="glass-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="glass-alert error">{{ session('error') }}</div>
    @endif

    <div class="split-layout">

        <!-- LEFT PANEL: Actions & Forms -->
        <div class="left-panel glass-panel">

            <!-- Context Switcher -->
            <div id="actionSwitch" class="action-switch">
                <p>What would you like to do?</p>
                <div class="switch-buttons">
                    <button class="btn-sm btn-primary" onclick="showForm('category')">New Category</button>
                    <button class="btn-sm btn-outline" onclick="showForm('product')">New Product</button>
                </div>
            </div>

            <!-- Category Form -->
            <div id="categoryForm" class="form-section hidden">
                <div class="form-header">
                    <h3><i class="fas fa-tags"></i> Add Category</h3>
                    <button class="btn-close-sm" onclick="resetForms()">&times;</button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="name" required placeholder="e.g. Electronics">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2" placeholder="Optional details..."></textarea>
                    </div>
                    <button type="submit" class="btn-block btn-primary">Save Category</button>
                </form>
            </div>

            <!-- Product Form -->
            <div id="productForm" class="form-section hidden">
                <div class="form-header">
                    <h3><i class="fas fa-box"></i> Add Product</h3>
                    <button class="btn-close-sm" onclick="resetForms()">&times;</button>
                </div>
                <form action="{{ route('admin.product.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required placeholder="e.g. Wireless Mouse">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="">Select Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ session('new_category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Added Price Field if supported by Model -->
                    <div class="form-group">
                        <label>Unit Price (FRW)</label>
                        <input type="number" name="unit_price" step="0.01" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2" placeholder="Product details..."></textarea>
                    </div>

                    <button type="submit" class="btn-block btn-primary">Save Product</button>
                </form>
            </div>

            <!-- Categories List (Mini) -->
            <div class="mini-list-section">
                <h4><i class="fas fa-list-ul"></i> Categories</h4>
                <div class="mini-list-wrapper custom-scrollbar">
                    <table class="mini-table">
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td class="actions">
                                    <button class="btn-icon-tiny edit"
                                        onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"><i
                                            class="fas fa-pen"></i></button>
                                    <button class="btn-icon-tiny delete" onclick="deleteCategory({{ $category->id }})"><i
                                            class="fas fa-trash"></i></button>
                                    <form id="del-cat-{{ $category->id }}"
                                        action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                        style="display:none">@csrf @method('DELETE')</form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted">No categories.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>

            <!-- Edit Category Modal (Inline) -->
            <div id="editCategoryForm" class="form-section hidden overlay-form">
                <div class="form-header">
                    <h3>Edit Category</h3>
                    <button class="btn-close-sm" onclick="closeEditCategory()">&times;</button>
                </div>
                <form id="updateCategoryFormTag" action="" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_cat_name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_cat_desc" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn-block btn-success">Update</button>
                </form>
            </div>

        </div>

        <!-- RIGHT PANEL: Products List -->
        <div class="right-panel glass-panel">
            <div class="panel-header">
                <div class="header-left">
                    <h2>Inventory Products</h2>
                    <span class="badge-count">{{ $products->total() }} items</span>
                </div>
                <div class="header-right">
                    <div class="toolbar-glass">
                        <!-- Search -->
                        <div class="search-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="productSearch" placeholder="Search inventory...">
                        </div>

                        <div class="divider"></div>

                        <!-- Date Filter & Exports -->
                        <form action="{{ route('admin.products.export.pdf') }}" method="GET" target="_blank"
                            class="filter-group">
                            <div class="date-inputs">
                                <input type="date" name="start_date" placeholder="Start">
                                <span class="separator">to</span>
                                <input type="date" name="end_date" placeholder="End">
                            </div>
                            <button type="submit" class="btn-icon-text danger" title="Export PDF">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </form>

                        <form action="{{ route('admin.products.export.excel') }}" method="GET">
                            <button type="submit" class="btn-icon-text success" title="Export Excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-container custom-scrollbar">
                <table class="glass-table" id="inventoryTable">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th width="100">Price</th>
                            <th width="80">Stock</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration + ($products->firstItem() ?? 1) - 1 }}</td>
                                <td>
                                    <div class="product-info">
                                        <span class="p-name">{{ $product->name }}</span>
                                        <small class="p-desc">{{ Str::limit($product->description, 30) }}</small>
                                    </div>
                                </td>
                                <td><span class="badge-category">{{ $product->category->name ?? 'None' }}</span></td>
                                <td class="font-mono">
                                    {{ $product->unit_price ? number_format($product->unit_price, 2) . ' FRW' : '-' }}
                                </td>
                                <td>
                                    @php $stock = $product->remaining_stock; @endphp
                                    <span class="badge-stock {{ $stock <= 0 ? 'out' : ($stock < 10 ? 'low' : 'good') }}">
                                        {{ $stock }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button class="btn-icon edit"
                                            onclick="editProduct({{ $product->id }}, '{{ $product->name }}', '{{ $product->category_id }}', '{{ $product->unit_price }}', `{{ $product->description }}`)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn-icon delete" onclick="confirmDeleteProduct({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="del-prod-{{ $product->id }}"
                                            action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                                            style="display:none">@csrf @method('DELETE')</form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No products found in inventory.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-footer">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal (Overlay) -->
<!-- Edit Product Modal (Overlay) -->
<div id="editProductModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color:var(--primary)"></i> Edit Product</h3>
            <button class="btn-close" onclick="closeEditProduct()">&times;</button>
        </div>
        <form id="updateProductForm" action="" method="POST"
            onsubmit="this.querySelector('.btn-save').innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Saving...'; this.querySelector('.btn-save').disabled=true;">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Name</label>
                <div class="input-with-icon">
                    <i class="fas fa-box"></i>
                    <input type="text" name="name" id="edit_prod_name" required placeholder="Product Name">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label>Category</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <select name="category_id" id="edit_prod_cat" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label>Unit Price</label>
                    <div class="input-with-icon">
                        <span
                            style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 10px; font-weight: 800;">FRW</span>
                        <input type="number" name="unit_price" id="edit_prod_price" step="0.01" placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="edit_prod_desc" rows="4" placeholder="Description..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-sm btn-outline" onclick="closeEditProduct()">Cancel</button>
                <button type="submit" class="btn-sm btn-primary btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>


<style>
    /* --- THEME EXTENSION --- */
    body {
        background: var(--body-bg);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--text-primary);
    }

    .products-wrapper {
        margin-left: 250px;
        padding: 30px;
        min-height: 100vh;
        background: var(--body-bg);
        color: var(--text-primary);
    }

    /* Alerts */
    .glass-alert {
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .glass-alert.success {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .glass-alert.error {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Layout */
    .split-layout {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }

    .left-panel {
        flex: 0 0 300px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: sticky;
        top: 25px;
    }

    .right-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        gap: 20px;
    }

    .glass-panel {
        background: var(--surface);
        border: 1px solid var(--header-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    /* --- LEFT PANEL COMPONENTS --- */
    .action-switch {
        text-align: center;
        padding: 25px 20px;
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--header-border);
    }

    .action-switch p {
        margin: 0 0 15px;
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
    }

    .switch-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-direction: column;
    }

    .switch-buttons button {
        width: 100%;
        padding: 10px;
    }

    .form-section {
        animation: fadeIn 0.3s ease;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--header-border);
    }

    .form-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close-sm {
        background: none;
        border: none;
        font-size: 18px;
        color: var(--text-muted);
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }

    .btn-close-sm:hover {
        color: #ef4444;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid var(--header-border);
        font-size: 13px;
        outline: none;
        transition: all 0.2s;
        background: var(--body-bg);
        color: var(--text-primary);
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: var(--primary);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .mini-list-section {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--header-border);
    }

    .mini-list-section h4 {
        font-size: 12px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin: 0 0 12px;
        letter-spacing: 0.8px;
        font-weight: 700;
    }

    .mini-list-wrapper {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .mini-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 4px;
        font-size: 12px;
    }

    .mini-table td {
        padding: 8px 10px;
        background: var(--body-bg);
        border: 1px solid transparent;
        color: var(--text-primary);
    }

    .mini-table tr td:first-child {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }

    .mini-table tr td:last-child {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }

    .mini-table .actions {
        text-align: right;
        white-space: nowrap;
        width: 60px;
    }

    .mini-table tr:hover td {
        background: var(--surface);
        border-color: var(--header-border);
    }

    /* --- RIGHT PANEL COMPONENTS --- */
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-left h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        display: inline-block;
        vertical-align: middle;
        color: var(--text-primary);
    }

    .badge-count {
        background: rgba(79, 70, 229, 0.1);
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 10px;
        vertical-align: middle;
    }

    /* TOOLBAR STYLES */
    .toolbar-glass {
        display: flex;
        align-items: center;
        gap: 15px;
        background: var(--surface);
        border: 1px solid var(--header-border);
        padding: 8px 15px;
        border-radius: 50px;
        box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.05);
    }

    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-wrapper i {
        position: absolute;
        left: 12px;
        color: var(--text-muted);
        font-size: 13px;
    }

    .search-wrapper input {
        border: none;
        background: var(--body-bg);
        padding: 8px 12px 8px 36px;
        border-radius: 20px;
        font-size: 13px;
        width: 180px;
        outline: none;
        transition: all 0.2s;
        color: var(--text-primary);
    }

    .search-wrapper input:focus {
        background: var(--surface);
        width: 220px;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
    }

    .divider {
        width: 1px;
        height: 24px;
        background: var(--header-border);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-inputs {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--body-bg);
        padding: 6px 12px;
        border-radius: 20px;
        border: 1px solid var(--header-border);
    }

    .date-inputs input {
        border: none;
        background: transparent;
        font-size: 12px;
        color: var(--text-primary);
        width: 105px;
        outline: none;
        font-family: inherit;
        cursor: pointer;
    }

    .date-inputs .separator {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        filter: invert(0.5);
    }

    .btn-icon-text {
        border: none;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        transition: transform 0.1s;
    }

    .btn-icon-text:hover {
        transform: translateY(-1px);
    }

    .btn-icon-text.danger {
        background: #ef4444;
        box-shadow: 0 2px 5px rgba(239, 68, 68, 0.2);
    }

    .btn-icon-text.success {
        background: #10b981;
        box-shadow: 0 2px 5px rgba(16, 185, 129, 0.2);
    }

    .btn-icon-text i {
        font-size: 12px;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--header-border);
    }

    .glass-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }

    .glass-table th {
        text-align: left;
        padding: 12px 15px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        background: var(--body-bg);
        border-bottom: 1px solid var(--header-border);
    }

    .glass-table tbody tr {
        background: var(--surface);
        transition: background 0.15s;
    }

    .glass-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.02);
    }

    .glass-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid var(--header-border);
        color: var(--text-primary);
    }

    .glass-table tr:last-child td {
        border-bottom: none;
    }

    .product-info .p-name {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 14px;
        margin-bottom: 2px;
    }

    .product-info .p-desc {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
        line-height: 1.4;
    }

    .badge-category {
        background: var(--body-bg);
        color: var(--text-muted);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        border: 1px solid var(--header-border);
        font-weight: 500;
    }

    .badge-stock {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        min-width: 30px;
        display: inline-block;
        text-align: center;
    }

    .badge-stock.good {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-stock.low {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .badge-stock.out {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .font-mono {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }

    /* Buttons */
    .btn-sm {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }

    .btn-primary:hover {
        filter: brightness(110%);
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--header-border);
        color: var(--text-primary);
    }

    .btn-outline:hover {
        background: var(--surface);
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-block {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--header-border);
        background: var(--body-bg);
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s;
    }

    .btn-icon:hover {
        background: var(--surface);
        color: var(--primary);
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .btn-icon.delete:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #ef4444;
    }

    .btn-icon-tiny {
        font-size: 11px;
        width: 24px;
        height: 24px;
    }

    .pagination-footer {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
    }

    .page-link {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        background: var(--surface);
        border: 1px solid var(--header-border);
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: var(--body-bg);
        border-color: var(--primary);
        color: var(--primary);
    }

    .page-item.active .page-link {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        background: var(--body-bg);
        pointer-events: none;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-glass {
        background: var(--surface);
        width: 500px;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: zoomIn 0.2s ease;
        border: 1px solid var(--header-border);
    }

    .modal-glass label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
        letter-spacing: 0.5px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 13px;
        pointer-events: none;
    }

    .modal-glass input,
    .modal-glass select,
    .modal-glass textarea {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 1px solid var(--header-border);
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        background: var(--body-bg);
        transition: all 0.2s;
        margin-bottom: 15px;
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
    }

    .modal-glass textarea {
        padding: 12px;
    }

    /* No icon padding for textarea */
    .modal-glass input:focus,
    .modal-glass select:focus,
    .modal-glass textarea:focus {
        border-color: var(--primary);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--header-border);
        padding-bottom: 15px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .btn-close {
        border: none;
        background: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
    }

    .btn-close:hover {
        color: var(--text-primary);
        transform: rotate(90deg);
    }

    .modal-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .hidden {
        display: none !important;
    }

    .row {
        display: flex;
        gap: 15px;
    }

    .col {
        flex: 1;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: var(--body-bg);
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    // --- UI Toggle ---
    function showForm(type) {
        document.getElementById('actionSwitch').classList.add('hidden');
        document.getElementById('editCategoryForm').classList.add('hidden');
        if (type === 'category') {
            document.getElementById('categoryForm').classList.remove('hidden');
            document.getElementById('productForm').classList.add('hidden');
        } else {
            document.getElementById('productForm').classList.remove('hidden');
            document.getElementById('categoryForm').classList.add('hidden');
        }
    }
    function resetForms() {
        document.getElementById('categoryForm').classList.add('hidden');
        document.getElementById('productForm').classList.add('hidden');
        document.getElementById('editCategoryForm').classList.add('hidden');
        document.getElementById('actionSwitch').classList.remove('hidden');
    }

    @if(session('new_category'))
        // Refill Product Form if redirected from category creation
        showForm('product');
    @endif

    // --- Search ---
    document.getElementById("productSearch").addEventListener("keyup", function () {
        let val = this.value.toLowerCase();
        document.querySelectorAll("#inventoryTable tbody tr").forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? "" : "none";
        });
    });

    // --- Actions ---
    function confirmDeleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            document.getElementById('del-prod-' + id).submit();
        }
    }
    function deleteCategory(id) {
        if (confirm('Delete Category? This might affect linked products.')) {
            document.getElementById('del-cat-' + id).submit();
        }
    }

    // --- Edit Modals ---
    function editProduct(id, name, catId, price, desc) {
        document.getElementById('editProductModal').classList.remove('hidden');
        document.getElementById('updateProductForm').action = "/admin/products/" + id;
        document.getElementById('edit_prod_name').value = name;
        document.getElementById('edit_prod_cat').value = catId;
        document.getElementById('edit_prod_price').value = price || '';
        document.getElementById('edit_prod_desc').value = desc;
    }
    function closeEditProduct() {
        document.getElementById('editProductModal').classList.add('hidden');
    }

    function editCategory(id, name, desc) {
        // Show inline editor in left panel
        document.getElementById('actionSwitch').classList.add('hidden');
        document.getElementById('categoryForm').classList.add('hidden');
        document.getElementById('productForm').classList.add('hidden');

        let container = document.getElementById('editCategoryForm');
        container.classList.remove('hidden');
        document.getElementById('updateCategoryFormTag').action = "/admin/categories/" + id;
        document.getElementById('edit_cat_name').value = name;
        document.getElementById('edit_cat_desc').value = desc;
    }
    function closeEditCategory() {
        resetForms();
    }

    // modal close on click outside
    window.onclick = function (e) {
        let m = document.getElementById('editProductModal');
        if (e.target == m) closeEditProduct();
    }
</script>