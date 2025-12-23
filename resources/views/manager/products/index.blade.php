@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <!-- Feedback Notifications -->
    @if(session('success'))
        <div class="glass-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="glass-alert danger">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="pro-header">
        <div>
            <h1>Inventory Intelligence</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Orchestrate your product portfolio and supply chain</p>
        </div>
        <div class="header-actions" style="display: flex; gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 0.7rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                <input type="date" id="report_start" class="filter-input" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--dark);">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 0.7rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                <input type="date" id="report_end" class="filter-input" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--dark);">
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

    <!-- Inventory Stats -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total SKUs</div>
                    <div class="value">{{ $stats['total_products'] }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-layer-group"></i> Active Portfolio
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Categories</div>
                    <div class="value">{{ $stats['total_categories'] }}</div>
                    <div class="stat-trend up">
                        Segmented Units
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Low Stock</div>
                    <div class="value">{{ $stats['low_stock'] }}</div>
                    <div class="stat-trend down">
                        Requires Attention
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Stock Out</div>
                    <div class="value">{{ $stats['out_of_stock'] }}</div>
                    <div class="stat-trend down">
                        Deficit Status
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-grid">
        <!-- Catalog Management -->
        <div class="catalog-section">
            <div class="pro-card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                    <div class="pro-search" style="flex: 1; max-width: 400px;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="internalSearch" placeholder="Search the ledger..." style="background: none; border: none; width: 100%; focus: outline-none;">
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick="openFormModal('productModal')" class="action-btn btn-primary" style="height: 38px;">
                            <i class="fas fa-plus"></i> New Product
                        </button>
                        <button onclick="openFormModal('categoryModal')" class="action-btn" style="height: 38px; background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                            <i class="fas fa-folder-plus"></i> Category
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="pro-table" id="productLedger">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Specifications</th>
                                <th>Classification</th>
                                <th>Inventory Status</th>
                                <th style="text-align: right;">Orchestration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td><span style="color: var(--secondary); font-weight: 600;">#{{ $product->id }}</span></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: var(--bg-main); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--dark);">{{ $product->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--secondary);">{{ Str::limit($product->description, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-processing">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                </td>
                                <td>
                                    @php
                                        $stock = $product->remaining_stock;
                                        $statusClass = $stock <= 0 ? 'status-cancelled' : ($stock < 10 ? 'status-pending' : 'status-completed');
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 8px; height: 8px; border-radius: 50%;" class="{{ $statusClass }}"></div>
                                        <span style="font-weight: 600;">{{ $stock }} in stock</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button onclick="viewProduct({{ $product->id }})" class="action-btn" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editProduct({{ $product->id }})" class="action-btn" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('manager.product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Archive this product SKU?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div style="text-align: center; padding: 4rem; color: var(--secondary);">
                                        <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                                        <h3>Empty Catalog</h3>
                                        <p>No products found in the current ledger.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="productModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Add New Product SKU</h3>
            <button onclick="closeFormModal('productModal')" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('manager.product.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Product Name</label>
                <input type="text" name="name" required placeholder="Enter formal name">
            </div>
            <div class="form-group">
                <label><i class="fas fa-folder"></i> Classification</label>
                <select name="category_id" required>
                    <option value="">-- Choose Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Specifications</label>
                <textarea name="description" placeholder="Product details..."></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-check-circle"></i> Initialize SKU
            </button>
        </form>
    </div>
</div>

<div id="categoryModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Establish Category</h3>
            <button onclick="closeFormModal('categoryModal')" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('manager.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-folder"></i> Category Name</label>
                <input type="text" name="name" required placeholder="General Classification">
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Definition</label>
                <textarea name="description" placeholder="Optional category scope..."></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-check-circle"></i> Save Category
            </button>
        </form>
    </div>
</div>

<style>
    .glass-modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        width: 90%;
        max-width: 500px;
        position: relative;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--glass-border);
    }
    .modal-header h3 { margin: 0; font-weight: 800; background: linear-gradient(90deg, var(--primary), var(--info)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .close-btn { background: none; border: none; font-size: 1.5rem; color: var(--secondary); cursor: pointer; }

    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.5rem; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        border-radius: 0.75rem;
        font-size: 0.9rem;
        color: var(--dark);
        transition: all 0.2s;
    }
    .form-group input:focus { border-color: var(--primary); background: var(--white); outline: none; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }

    .glass-alert {
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid var(--glass-border);
        animation: slideDown 0.4s ease-out;
        box-shadow: var(--shadow-sm);
    }
    .glass-alert.success { background: rgba(34, 197, 94, 0.1); color: #15803d; border-color: rgba(34, 197, 94, 0.2); }
    .glass-alert.danger { background: rgba(239, 68, 68, 0.1); color: #b91c1c; border-color: rgba(239, 68, 68, 0.2); }
    .glass-alert i { font-size: 1.25rem; }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<!-- Dynamic Modals -->
<div id="dynamicModal" class="glass-modal">
    <div class="modal-content pro-card" id="dynamicModalContent">
        <!-- Content injected via JS -->
    </div>
</div>

<script>
function openFormModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeFormModal(id) { document.getElementById(id).style.display = 'none'; }

function saveCategory() {
    document.getElementById('categoryForm').submit();
}

function downloadReport(type) {
    const start = document.getElementById('report_start').value;
    const end = document.getElementById('report_end').value;
    if(!start || !end) {
        alert('Please select both start and end date for catalog audit.');
        return;
    }
    const baseUrl = type === 'pdf' ? "{{ route('manager.products.export.pdf') }}" : "{{ route('manager.products.export.excel') }}";
    window.location.href = `${baseUrl}?start_date=${start}&end_date=${end}`;
}

const categories = @json($categories);

function viewProduct(id) {
    const row = document.querySelector(`tr:has(td span:contains('#${id}'))`); 
    // Fallback: search in a more robust way as the selector above is jQuery-like but needs vanilla JS
    const tr = Array.from(document.querySelectorAll('#productLedger tbody tr')).find(r => r.cells[0].innerText.includes(`#${id}`));
    if(!tr) return;

    const name = tr.cells[1].querySelector('div > div:first-child').innerText;
    const desc = tr.cells[1].querySelector('div > div:last-child').innerText;
    const cat = tr.cells[2].innerText;
    const stock = tr.cells[3].innerText;

    const content = `
        <div class="modal-header">
            <h3>Product Intelligence View</h3>
            <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
        </div>
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 80px; height: 80px; background: var(--bg-main); border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem; border: 1px solid var(--glass-border);">
                <i class="fas fa-cube"></i>
            </div>
            <h2 style="font-weight: 800; margin-bottom: 0.5rem;">${name}</h2>
            <span class="status-badge status-processing">${cat}</span>
        </div>
        <div class="pro-card" style="background: var(--bg-main); border: 1px solid var(--glass-border); margin-bottom: 1.5rem;">
            <div style="padding: 1rem;">
                <div style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Description</div>
                <div style="color: var(--dark); line-height: 1.6;">${desc}</div>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="pro-card" style="padding: 1rem; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--secondary);">SKU IDENTIFIER</div>
                <div style="font-weight: 700;">#${id}</div>
            </div>
            <div class="pro-card" style="padding: 1rem; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--secondary);">CURRENT STOCK</div>
                <div style="font-weight: 700;">${stock}</div>
            </div>
        </div>
    `;
    document.getElementById('dynamicModalContent').innerHTML = content;
    openFormModal('dynamicModal');
}

function editProduct(id) {
    const tr = Array.from(document.querySelectorAll('#productLedger tbody tr')).find(r => r.cells[0].innerText.includes(`#${id}`));
    if(!tr) return;

    const name = tr.cells[1].querySelector('div > div:first-child').innerText;
    const desc = tr.cells[1].querySelector('div > div:last-child').innerText;
    const catName = tr.cells[2].innerText.trim();
    
    let catOptions = '';
    categories.forEach(c => {
        catOptions += `<option value="${c.id}" ${c.name === catName ? 'selected' : ''}>${c.name}</option>`;
    });

    const updateUrl = `{{ url('manager/products') }}/${id}`;

    const content = `
        <div class="modal-header">
            <h3>Modify Product SKU</h3>
            <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
        </div>
        <form action="${updateUrl}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Formal Name</label>
                <input type="text" name="name" value="${name}" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-folder"></i> Classification</label>
                <select name="category_id" required>
                    ${catOptions}
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Specifications</label>
                <textarea name="description" rows="4">${desc === 'No description' ? '' : desc}</textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-sync-alt"></i> Commit Changes
            </button>
        </form>
    `;
    document.getElementById('dynamicModalContent').innerHTML = content;
    openFormModal('dynamicModal');
}

document.getElementById('internalSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productLedger tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

window.onclick = function(event) {
    if (event.target.classList.contains('glass-modal')) {
        event.target.style.display = 'none';
    }
}
</script>

