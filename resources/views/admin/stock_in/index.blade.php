@include('layouts.header')
@include('layouts.sidebar')

<div class="page-wrapper main-content">
    
    <!-- Success/Error Feedback -->
    @if(session('success'))
        <div class="glass-alert success" id="alert-success">
            <div class="alert-content">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            <button class="btn-close-alert" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="glass-alert error" id="alert-error">
            <div class="alert-content">
                <i class="fas fa-exclamation-triangle"></i>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="btn-close-alert" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    <div class="split-layout">
        
        <!-- LEFT PANEL: Add Stock Form -->
        <div class="left-panel glass-card slide-in-left">
            <div class="panel-header">
                <h3><i class="fas fa-plus-circle"></i> Add Stock</h3>
                <a href="{{ route('admin.stock_in.addSupplier') }}" class="btn-link-sm" title="Add Supplier">
                    <i class="fas fa-user-plus"></i>
                </a>
            </div>

            <form action="{{ route('admin.stock_in.store') }}" method="POST" id="addStockForm">
                @csrf
                
                <div class="form-group">
                    <label>Product <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-box"></i>
                        <select name="product_id" required>
                            <option value="">Select Product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Supplier</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-truck"></i>
                        <select name="supplier_id">
                            <option value="">Select Supplier...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row-group">
                    <div class="col-half">
                        <label>Quantity <span class="req">*</span></label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-sort-numeric-up"></i>
                            <input type="number" name="quantity" id="addQty" required min="1" oninput="calculateTotal('add')">
                        </div>
                    </div>
                    <div class="col-half">
                        <label>Unit Cost <span class="req">*</span></label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-dollar-sign"></i>
                            <input type="number" name="unit_cost" id="addCost" required step="0.01" oninput="calculateTotal('add')">
                        </div>
                    </div>
                </div>

                <div class="form-group total-box">
                    <label>Estimated Total Cost</label>
                    <div class="total-display" id="addTotalDisplay">FRW 0.00</div>
                </div>

                <div class="form-group">
                    <label>Type <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-tag"></i>
                        <select name="type" required>
                            <option value="purchase">Purchase</option>
                            <option value="return">Return</option>
                            <option value="production">Production</option>
                            <option value="donation">Donation</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Date <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" name="stock_in_date" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Note</label>
                    <textarea name="note" rows="2" placeholder="Optional notes..."></textarea>
                </div>

                <button type="submit" class="btn-primary-block">
                    <i class="fas fa-save"></i> Save Entry
                </button>
            </form>
        </div>


        <!-- RIGHT PANEL: List & Analytics -->
        <div class="right-panel">
            
            <!-- Analytics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon-bg blue"><i class="fas fa-list"></i></div>
                    <div class="stat-info">
                        <h4>Total Entries</h4>
                        <span>{{ $stockIns->total() }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg green"><i class="fas fa-cubes"></i></div>
                    <div class="stat-info">
                        <h4>Total Quantity</h4>
                        <span>{{ $stockIns->sum('quantity') }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg orange"><i class="fas fa-coins"></i></div>
                    <div class="stat-info">
                        <h4>Total Value</h4>
                        <span>FRW {{ number_format($stockIns->sum('total_cost'), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Toolbar (Search + Filters + Export) -->
            <div class="toolbar-glass">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search records...">
                </div>
                
                <div class="divider"></div>

                <form action="{{ route('admin.stock_in.index') }}" method="GET" class="filter-form">
                    <div class="date-group">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" title="Start Date">
                        <span>to</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" title="End Date">
                    </div>
                    <button type="submit" class="btn-icon circle" title="Apply Filter"><i class="fas fa-filter"></i></button>
                    @if(request('start_date'))
                        <a href="{{ route('admin.stock_in.index') }}" class="btn-icon circle red" title="Clear"><i class="fas fa-times"></i></a>
                    @endif
                </form>

                <div class="divider"></div>

                <!-- Export (Using Current URL params logic usually, but specifically linking to controllers) -->
                <div class="export-group">
                    <form action="{{ route('admin.stock_in.pdf') }}" method="GET" target="_blank">
                        <!-- Pass current filters if needed via JS or hidden inputs, simplistic here -->
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn-pill pdf"><i class="fas fa-file-pdf"></i> PDF</button>
                    </form>
                    <form action="{{ route('admin.stock_in.excel') }}" method="GET">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn-pill excel"><i class="fas fa-file-excel"></i> Excel</button>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="glass-card table-wrapper custom-scroll">
                <table class="glass-table" id="stockTable">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Product</th>
                            <th>Supplier</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockIns as $stock)
                        <tr class="fade-in-row">
                            <td>{{ $loop->iteration + ($stockIns->firstItem() ?? 1) - 1 }}</td>
                            <td><strong>{{ $stock->product->name ?? 'N/A' }}</strong></td>
                            <td>{{ $stock->supplier->name ?? '-' }}</td>
                            <td><span class="badge {{ $stock->type }}">{{ ucfirst($stock->type) }}</span></td>
                            <td>{{ $stock->quantity }}</td>
                            <td>FRW {{ number_format($stock->unit_cost, 2) }}</td>
                            <td><strong>FRW {{ number_format($stock->total_cost, 2) }}</strong></td>
                            <td class="text-muted">{{ $stock->stock_in_date->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-icon edit" onclick='editStock(@json($stock))'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn-icon delete" onclick="deleteStock({{ $stock->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-form-{{ $stock->id }}" action="{{ route('admin.stock_in.destroy', $stock->id) }}" method="POST" style="display:none">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="fas fa-box-open"></i> No stock records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $stockIns->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3>Edit Stock Entry</h3>
            <button class="btn-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            
            <div class="form-group">
                <label>Product</label>
                <!-- Disabled product selection on edit to avoid confusion or allow if needed. Usually stock entry product shouldn't change, but allowing for now -->
                <!-- Display only for safety? Or full edit? Let's assume full edit but pre-filled -->
                 <div class="input-icon-wrapper">
                    <i class="fas fa-box"></i>
                    <select name="product_id" id="editProduct" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Supplier -->
            <div class="form-group">
                <label>Supplier</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-truck"></i>
                    <select name="supplier_id" id="editSupplier">
                        <option value="">None</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row-group">
                <div class="col-half">
                    <label>Qty</label>
                    <input type="number" name="quantity" id="editQty" required min="1" oninput="calculateTotal('edit')">
                </div>
                <div class="col-half">
                    <label>Cost</label>
                    <input type="number" name="unit_cost" id="editCost" required step="0.01" oninput="calculateTotal('edit')">
                </div>
            </div>

            <div class="form-group total-box small">
                <label>New Total:</label> 
                <span id="editTotalDisplay">FRW 0.00</span>
            </div>

            <div class="row-group">
                <div class="col-half">
                    <label>Type</label>
                    <select name="type" id="editType" required>
                        <option value="purchase">Purchase</option>
                        <option value="return">Return</option>
                        <option value="production">Production</option>
                        <option value="donation">Donation</option>
                    </select>
                </div>
                <div class="col-half">
                    <label>Date</label>
                    <input type="date" name="stock_in_date" id="editDate" required>
                </div>
            </div>
             <div class="form-group">
                <label>Note</label>
                <textarea name="note" id="editNote" rows="2"></textarea>
            </div>

            <button type="submit" class="btn-primary-block">Update Entry</button>
        </form>
    </div>
</div>

<style>
/* --- THEME & UTILS --- */
body { background: var(--body-bg); color: var(--text-primary); font-family: 'Inter', sans-serif; }
.page-wrapper { margin-left: 250px; padding: 25px; transition: margin 0.3s; background: var(--body-bg); min-height: 100vh; }
.text-muted { color: var(--text-muted) !important; }

/* Alerts */
.glass-alert {
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center;
    animation: slideDown 0.3s ease;
}
.glass-alert.success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
.glass-alert.error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; }
.btn-close-alert { background: none; border: none; font-size: 18px; cursor: pointer; color: inherit; opacity: 0.7; }
.error-list { margin: 0; padding-left: 20px; }

/* Layout */
.split-layout { display: flex; gap: 25px; align-items: flex-start; }
.left-panel { flex: 0 0 300px; position: sticky; top: 20px; }
.right-panel { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 20px; }

/* Cards & Glass */
.glass-card { background: var(--surface); border: 1px solid var(--header-border); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); padding: 20px; }
.panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--header-border); }
.panel-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
.req { color: #ef4444; }

/* Forms */
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; }
.input-icon-wrapper { position: relative; }
.input-icon-wrapper i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px; pointer-events: none; }
input, select, textarea {
    width: 100%; box-sizing: border-box; padding: 10px 12px 10px 36px;
    border: 1px solid var(--header-border); border-radius: 8px; background: var(--body-bg);
    font-size: 13px; color: var(--text-primary); outline: none; transition: all 0.2s; font-family: inherit;
}
textarea { padding: 10px; height: 70px; }
input:focus, select:focus, textarea:focus { border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

/* Remove calendar icon overlap for date inputs */
input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; filter: invert(0.5); }

.row-group { display: flex; gap: 10px; }
.col-half { flex: 1; }

.total-box { background: var(--body-bg); padding: 10px; border-radius: 8px; text-align: center; border: 1px dashed var(--header-border); }
.total-display { font-size: 18px; font-weight: 800; color: #10b981; }
.small .total-display { font-size: 14px; }

.btn-primary-block { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
.btn-primary-block:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
.btn-link-sm { color: var(--primary); font-size: 14px; transition: transform 0.2s; }
.btn-link-sm:hover { transform: scale(1.1); }

/* Stats */
.stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
.stat-card { background: var(--surface); border-radius: 12px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 15px; border: 1px solid var(--header-border); }
.icon-bg { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.icon-bg.blue { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
.icon-bg.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.icon-bg.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.stat-info h4 { margin: 0; font-size: 11px; color: var(--text-muted); text-transform: uppercase; }
.stat-info span { font-size: 18px; font-weight: 800; color: var(--text-primary); }

/* Toolbar */
.toolbar-glass { background: var(--surface); border-radius: 50px; padding: 6px 15px; display: flex; align-items: center; gap: 15px; border: 1px solid var(--header-border); box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
.search-wrapper { position: relative; }
.search-wrapper i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-wrapper input { padding: 6px 15px 6px 32px; border-radius: 20px; background: var(--body-bg); border: none; width: 180px; color: var(--text-primary); }
.search-wrapper input:focus { background: var(--surface); box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); }
.divider { width: 1px; height: 20px; background: var(--header-border); }
.filter-form { display: flex; gap: 8px; align-items: center; }
.date-group { background: var(--body-bg); padding: 4px 10px; border-radius: 20px; display: flex; align-items: center; gap: 5px; border: 1px solid var(--header-border); }
.date-group span { color: var(--text-muted); font-size: 12px; }
.date-group input { border: none; background: transparent; padding: 0; width: 110px; font-size: 12px; cursor: pointer; color: var(--text-primary); }
.btn-icon.circle { width: 28px; height: 28px; border-radius: 50%; background: var(--body-bg); color: var(--text-muted); display: flex; align-items: center; justify-content: center; border: 1px solid var(--header-border); cursor: pointer; transition: all 0.2s; }
.btn-icon.circle:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-icon.circle.red:hover { background: #ef4444; color: white; border-color: #ef4444; }

.export-group { display: flex; gap: 8px; margin-left: auto; }
.btn-pill { padding: 6px 15px; border-radius: 20px; border: none; color: white; font-weight: 600; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: transform 0.1s; }
.btn-pill:hover { transform: translateY(-1px); }
.btn-pill.pdf { background: #ef4444; }
.btn-pill.excel { background: #10b981; }

/* Table */
.glass-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; font-size: 12px; }
.glass-table th { text-align: left; padding: 10px 15px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 11px; }
.glass-table tbody tr { background: var(--surface); transition: transform 0.1s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-radius: 8px; border: 1px solid var(--header-border); }
.glass-table tbody tr h4, .glass-table tbody tr strong { color: var(--text-primary); }
.glass-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.glass-table td { padding: 12px 15px; vertical-align: middle; border-top: 1px solid var(--body-bg); border-bottom: 1px solid var(--body-bg); color: var(--text-primary); }
.glass-table td:first-child { border-left: 1px solid var(--body-bg); border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
.glass-table td:last-child { border-right: 1px solid var(--body-bg); border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

.badge { padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.badge.purchase { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
.badge.return { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
.badge.production { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.badge.donation { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

.actions { display: flex; gap: 6px; }
.btn-icon { width: 28px; height: 28px; border-radius: 6px; background: var(--body-bg); color: var(--text-muted); border: 1px solid var(--header-border); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.btn-icon:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-icon.delete:hover { background: #ef4444; border-color: #ef4444; }

.empty-state { text-align: center; padding: 40px; color: var(--text-muted); font-size: 14px; }
.empty-state i { font-size: 24px; margin-bottom: 10px; display: block; opacity: 0.5; }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
.modal-glass { background: var(--surface); padding: 25px; border-radius: 16px; width: 450px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: zoomIn 0.2s ease; border: 1px solid var(--header-border); }
.hidden { display: none !important; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.modal-header h3 { margin: 0; color: var(--text-primary); font-size: 18px; }
.btn-close { background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer; }

@keyframes slideInLeft { from{opacity:0; transform:translateX(-20px);} to{opacity:1; transform:translateX(0);} }
@keyframes slideDown { from{opacity:0; transform:translateY(-10px);} to{opacity:1; transform:translateY(0);} }
@keyframes zoomIn { from{opacity:0; transform:scale(0.95);} to{opacity:1; transform:scale(1);} }
.slide-in-left { animation: slideInLeft 0.4s ease-out; }

/* PAGINATION STYLES */
.pagination {
    display: flex;
    justify-content: center;
    padding-left: 0;
    list-style: none;
    gap: 5px;
}
.page-item .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: var(--primary);
    background-color: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 600;
}
.page-item .page-link:hover {
    background-color: var(--body-bg);
    color: var(--primary);
    border-color: var(--primary);
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: var(--primary);
    border-color: var(--primary);
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
}
.page-item.disabled .page-link {
    color: var(--secondary);
    pointer-events: none;
    cursor: auto;
    background-color: var(--body-bg);
    border-color: var(--header-border);
    opacity: 0.6;
}

@media (max-width: 1024px) {
    .split-layout { flex-direction: column; }
    .left-panel { min-width: 100%; position: static; }
    .page-wrapper { margin-left: 0; padding: 15px; }
    .stats-grid { grid-template-columns: 1fr; }
}
</style>
<script>
// --- Client Search ---
document.getElementById('tableSearch').addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});

// --- Dynamic Total Calculation ---
function calculateTotal(prefix) {
    const qty = parseFloat(document.getElementById(prefix + 'Qty').value) || 0;
    const cost = parseFloat(document.getElementById(prefix + 'Cost').value) || 0;
    const total = qty * cost;
    
    // Animate the update slightly
    const display = document.getElementById(prefix + 'TotalDisplay');
    display.innerText = 'FRW ' + total.toFixed(2);
}

// --- Modals ---
function editStock(data) {
    document.getElementById('editModal').classList.remove('hidden');
    
    // Populate form
    const form = document.getElementById('editForm');
    form.action = '/admin/stock_in/' + data.id;
    
    document.getElementById('editProduct').value = data.product_id;
    document.getElementById('editSupplier').value = data.supplier_id || '';
    document.getElementById('editQty').value = data.quantity;
    document.getElementById('editCost').value = data.unit_cost;
    document.getElementById('editType').value = data.type;
    
    // Format date YYYY-MM-DD
    const dateVal = data.stock_in_date.split('T')[0]; // Assuming ISO string or formatted from blade
    document.getElementById('editDate').value = dateVal;
    
    document.getElementById('editNote').value = data.note || '';
    
    calculateTotal('edit');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deleteStock(id) {
    if(confirm('Are you sure you want to delete this record? This affects total stock calculations.')) {
        document.getElementById('del-form-' + id).submit();
    }
}

// Close modal on outside click
window.onclick = function(e) {
    const m = document.getElementById('editModal');
    if (e.target === m) closeEditModal();
}
</script>
