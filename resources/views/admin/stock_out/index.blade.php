@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-wrapper main-content">
    
    <!-- Success/Error Feedback -->
    <div id="alertContainer"></div>
    
    <div class="split-layout">
        
        <!-- LEFT PANEL: Stock Out Form -->
        <div class="left-panel glass-card slide-in-left">
            <div class="panel-header">
                <h3><i class="fas fa-arrow-circle-down"></i> Record Stock Out</h3>
                <button class="btn-link-sm" onclick="openCustomerModal()" title="Quick Add Customer">
                    <i class="fas fa-user-plus"></i>
                </button>
            </div>

            <div id="formMessage" class="message-box"></div>

            <form id="stockOutForm">
                @csrf
                
                <div class="form-group">
                    <label>Customer <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-user"></i>
                        <select name="customer_id" id="customerSelect" required>
                            <option value="">Select Customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Product <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-box"></i>
                        <select name="product_id" id="productSelect" required>
                            <option value="">Select Product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row-group">
                    <div class="col-half">
                        <label>Quantity <span class="req">*</span></label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-sort-numeric-down"></i>
                            <input type="number" name="quantity" id="stockQty" required min="1" value="1" oninput="calculateTotal()">
                        </div>
                    </div>
                    <div class="col-half">
                        <label>Unit Price <span class="req">*</span></label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-dollar-sign"></i>
                            <input type="number" name="unit_price" id="unitPrice" required step="0.01" oninput="calculateTotal()">
                        </div>
                    </div>
                </div>

                <div class="form-group total-box">
                    <label>Total Amount</label>
                    <div class="total-display" id="totalDisplay">FRW 0.00</div>
                </div>

                <div class="form-group">
                    <label>Type <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-tag"></i>
                        <select name="type" id="typeSelect" required>
                            <option value="sale">Sale</option>
                            <option value="return">Return</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Date <span class="req">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" name="stock_out_date" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Note</label>
                    <textarea name="note" rows="2" placeholder="Optional notes..."></textarea>
                </div>

                <button type="submit" class="btn-primary-block">
                    <i class="fas fa-save"></i> Save Transaction
                </button>
            </form>
        </div>


        <!-- RIGHT PANEL: Analytics & List -->
        <div class="right-panel">
            
            <!-- Page Title -->
            <div class="page-title-section">
                <h2><i class="fas fa-arrow-circle-down"></i> Stock Out Management</h2>
                <p class="subtitle">Track and manage all outgoing inventory transactions</p>
            </div>

            <!-- Analytics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon-bg blue"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <h4>Total Sales</h4>
                        <span>{{ $totalSoldQuantity ?? 0 }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg green"><i class="fas fa-cubes"></i></div>
                    <div class="stat-info">
                        <h4>Current Stock</h4>
                        <span>{{ ($totalStockIn ?? 0) - ($totalSoldQuantity ?? 0) }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg orange"><i class="fas fa-coins"></i></div>
                    <div class="stat-info">
                        <h4>Revenue</h4>
                        <span>FRW {{ number_format($profit ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg red"><i class="fas fa-undo"></i></div>
                    <div class="stat-info">
                        <h4>Returns</h4>
                        <span>FRW {{ number_format($loss ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Toolbar (Search + Filters + Export) -->
            <div class="toolbar-glass">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="tableSearch" placeholder="Search transactions...">
                </div>
                
                <div class="divider"></div>

                <form action="{{ route('admin.stockout.index') }}" method="GET" class="filter-form">
                    <div class="date-group">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" title="Start Date">
                        <span>to</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" title="End Date">
                    </div>
                    <button type="submit" class="btn-icon circle" title="Apply Filter"><i class="fas fa-filter"></i></button>
                    @if(request('start_date'))
                        <a href="{{ route('admin.stockout.index') }}" class="btn-icon circle red" title="Clear"><i class="fas fa-times"></i></a>
                    @endif
                </form>

                <div class="divider"></div>

                <!-- Export & Actions -->
                <div class="export-group">
                    <button class="btn-pill daily" id="dailyTransactionsBtn"><i class="fas fa-calendar-day"></i> Today</button>
                    <button class="btn-pill stock" id="stockAvailableBtn"><i class="fas fa-warehouse"></i> Stock</button>
                    <button onclick="window.location.href='{{ url('admin/stock_out/invoice') }}'" class="btn-pill invoice"><i class="fas fa-file-invoice"></i> Invoice</button>
                    <form action="{{ route('admin.stockout.report.pdf') }}" method="GET" target="_blank" style="display: inline;">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn-pill pdf"><i class="fas fa-file-pdf"></i> PDF</button>
                    </form>
                    <form action="{{ route('admin.stockout.report.excel') }}" method="GET" style="display: inline;">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn-pill excel"><i class="fas fa-file-excel"></i> Excel</button>
                    </form>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="glass-card table-wrapper custom-scroll">
                <div class="table-header">
                    <h3><i class="fas fa-list"></i> All Transactions</h3>
                    <span class="record-count">
                        Showing {{ $stockOuts->firstItem() ?? 0 }} to {{ $stockOuts->lastItem() ?? 0 }} of {{ $stockOuts->total() }} records
                    </span>
                </div>
                <table class="glass-table" id="stockTable">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOuts as $index => $out)
                        <tr class="fade-in-row">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $out->customer->name ?? 'N/A' }}</strong></td>
                            <td>{{ $out->product->name ?? 'Product #'.$out->product_id }}</td>
                            <td><span class="badge {{ $out->type }}">{{ ucfirst($out->type) }}</span></td>
                            <td>{{ $out->quantity }}</td>
                            <td>FRW {{ number_format($out->unit_price, 2) }}</td>
                            <td><strong>FRW {{ number_format($out->quantity * $out->unit_price, 2) }}</strong></td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($out->stock_out_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <button class="btn-icon edit" onclick='editStockOut(@json($out))'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn-icon delete" onclick="deleteStockOut({{ $out->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="fas fa-box-open"></i> No stock-out transactions found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $stockOuts->links() }}
            </div>

        </div>
    </div>
</div>

<!-- Customer Modal -->
<div id="customerModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3>Add New Customer</h3>
            <button class="btn-close" onclick="closeCustomerModal()">&times;</button>
        </div>
        <form id="saveCustomerForm">
            @csrf
            
            <div class="form-group">
                <label>Full Name <span class="req">*</span></label>
                <input type="text" name="name" required placeholder="Enter customer name">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="customer@example.com">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="+250 XXX XXX XXX">
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" rows="2" placeholder="Customer address..."></textarea>
            </div>

            <button type="submit" class="btn-primary-block">
                <i class="fas fa-save"></i> Save Customer
            </button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3>Edit Stock Out Transaction</h3>
            <button class="btn-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editStockOutForm">
            @csrf
            <input type="hidden" name="id" id="editId">
            
            <div class="row-group">
                <div class="col-half">
                    <label>Quantity</label>
                    <input type="number" name="quantity" id="editQty" required min="1" oninput="calculateEditTotal()">
                </div>
                <div class="col-half">
                    <label>Unit Price</label>
                    <input type="number" name="unit_price" id="editUnitPrice" required step="0.01" oninput="calculateEditTotal()">
                </div>
            </div>

            <div class="form-group total-box small">
                <label>New Total:</label> 
                <span id="editTotalDisplay">FRW 0.00</span>
            </div>

            <div class="form-group">
                <label>Type</label>
                <select name="type" id="editType" required>
                    <option value="sale">Sale</option>
                    <option value="return">Return</option>
                </select>
            </div>

            <div class="form-group">
                <label>Note</label>
                <textarea name="note" id="editNote" rows="2"></textarea>
            </div>

            <button type="submit" class="btn-primary-block">Update Transaction</button>
        </form>
    </div>
</div>

<!-- Daily Transactions Modal -->
<div id="dailyTransactionsModal" class="modal-overlay hidden">
    <div class="modal-glass modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-day"></i> Today's Transactions</h3>
            <button class="btn-close" onclick="closeDailyTransactionsModal()">&times;</button>
        </div>
        <div id="dailyTransactionsContent">
            <p style="text-align: center; padding: 20px; color: var(--text-light);">Loading...</p>
        </div>
    </div>
</div>

<!-- Stock Overview Modal -->
<div id="stockOverviewModal" class="modal-overlay hidden">
    <div class="modal-glass modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-warehouse"></i> Stock Overview</h3>
            <button class="btn-close" onclick="closeStockOverviewModal()">&times;</button>
        </div>
        <div id="stockOverviewContent">
            <p style="text-align: center; padding: 20px; color: var(--text-light);">Loading...</p>
        </div>
    </div>
</div>

<style>
/* --- THEME --- */
.page-wrapper { width: 80% !important; margin-left: 222px !important; padding: 25px 25px 50px 25px; transition: margin 0.3s; min-height: 100vh; box-sizing: border-box; background: var(--body-bg); color: var(--text-primary); font-family: 'Inter', sans-serif; font-size: 13px; margin: 0; }
.main-content { max-width: 100%; margin: 0 auto; }

/* Alerts */
#alertContainer { position: fixed; top: 80px; right: 25px; z-index: 10000; max-width: 400px; }
.glass-alert {
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center;
    animation: slideInRight 0.3s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.glass-alert.success { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; border-left: 4px solid #10b981; }
.glass-alert.error { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; border-left: 4px solid #ef4444; }
.alert-content { display: flex; align-items: center; gap: 10px; font-weight: 600; }
.btn-close-alert { background: none; border: none; font-size: 18px; cursor: pointer; color: inherit; opacity: 0.7; }
.btn-close-alert:hover { opacity: 1; }

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.message-box { padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 12px; display: none; }
.message-box.success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
.message-box.error { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

/* Layout */
.split-layout { display: flex; gap: 20px; align-items: flex-start; margin-top: 15px; }
.left-panel { flex: 0 0 320px; position: sticky; top: 90px; max-height: calc(100vh - 110px); overflow-y: auto; }
.right-panel { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 12px; }

/* Cards & Glass */
.glass-card { background: var(--surface); border: 1px solid var(--header-border); border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 20px; }
.panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--header-border); }
.panel-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
.req { color: #ef4444; }

/* Page Title */
.page-title-section { margin-bottom: 12px; }
.page-title-section h2 { margin: 0 0 3px 0; font-size: 20px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.page-title-section h2 i { color: var(--primary); font-size: 18px; }
.page-title-section .subtitle { margin: 0; color: var(--text-muted); font-size: 13px; }

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

.row-group { display: flex; gap: 10px; }
.col-half { flex: 1; }

.total-box { background: var(--body-bg); padding: 10px; border-radius: 8px; text-align: center; border: 1px dashed var(--header-border); }
.total-display { font-size: 18px; font-weight: 800; color: #10b981; }
.small .total-display { font-size: 14px; }

.btn-primary-block { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
.btn-primary-block:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3); }
.btn-link-sm { color: var(--primary); font-size: 16px; transition: transform 0.2s; background: none; border: none; cursor: pointer; }
.btn-link-sm:hover { transform: scale(1.1); }

/* Stats */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; margin-bottom: 12px; }
.stat-card { background: var(--surface); border-radius: 10px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 12px; border: 1px solid var(--header-border); }
.icon-bg { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; }
.icon-bg.blue { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
.icon-bg.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.icon-bg.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.icon-bg.red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
.stat-info h4 { margin: 0 0 2px 0; font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }
.stat-info span { font-size: 16px; font-weight: 800; color: var(--text-primary); }

/* Toolbar */
.toolbar-glass { background: var(--surface); border-radius: 40px; padding: 6px 12px; display: flex; align-items: center; gap: 10px; border: 1px solid var(--header-border); box-shadow: 0 2px 6px rgba(0,0,0,0.04); flex-wrap: wrap; }
.search-wrapper { position: relative; }
.search-wrapper input { padding: 5px 12px 5px 28px; border-radius: 18px; background: var(--body-bg); border: none; width: 160px; font-size: 12px; color: var(--text-primary); }
.search-wrapper input:focus { background: var(--surface); box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); }
.search-wrapper i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12px; }
.divider { width: 1px; height: 18px; background: var(--header-border); }
.filter-form { display: flex; gap: 6px; align-items: center; }
.date-group { background: var(--body-bg); padding: 3px 8px; border-radius: 18px; display: flex; align-items: center; gap: 4px; border: 1px solid var(--header-border); }
.date-group input { border: none; background: transparent; padding: 0; width: 90px; font-size: 11px; cursor: pointer; color: var(--text-primary); }
.btn-icon.circle { width: 26px; height: 26px; border-radius: 50%; background: var(--body-bg); color: var(--text-muted); display: flex; align-items: center; justify-content: center; border: 1px solid var(--header-border); cursor: pointer; transition: all 0.2s; font-size: 11px; }
.btn-icon.circle:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-icon.circle.red:hover { background: #ef4444; color: white; border-color: #ef4444; }

.export-group { display: flex; gap: 6px; flex-wrap: wrap; }
.btn-pill { padding: 5px 12px; border-radius: 18px; border: none; color: white; font-weight: 600; font-size: 11px; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s; white-space: nowrap; }
.btn-pill:hover { transform: translateY(-1px); box-shadow: 0 3px 10px rgba(0,0,0,0.12); }
.btn-pill i { font-size: 10px; }
.btn-pill.pdf { background: #ef4444; }
.btn-pill.excel { background: #10b981; }
.btn-pill.invoice { background: #8b5cf6; }
.btn-pill.stock { background: #3b82f6; }
.btn-pill.daily { background: #f59e0b; }

/* Table */
.table-wrapper { max-height: 650px; overflow-y: auto; overflow-x: auto; margin-top: 0; }
.table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--header-border); }
.table-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 6px; }
.table-header h3 i { font-size: 14px; }
.table-header .record-count { color: var(--text-muted); font-size: 11px; font-weight: 600; background: var(--body-bg); padding: 3px 10px; border-radius: 10px; border: 1px solid var(--header-border); }
.glass-table { width: 100%; border-collapse: separate; border-spacing: 0 4px; font-size: 12px; }
.glass-table th { text-align: left; padding: 8px 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 10px; position: sticky; top: 0; background: var(--body-bg); z-index: 10; border-bottom: 1px solid var(--header-border); }
.glass-table tbody tr { background: var(--surface); transition: transform 0.1s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-radius: 6px; border: 1px solid var(--header-border); }
.glass-table tbody tr:hover { transform: translateY(-1px); box-shadow: 0 3px 10px rgba(0,0,0,0.05); }
.glass-table td { padding: 10px 12px; vertical-align: middle; border-top: 1px solid var(--body-bg); border-bottom: 1px solid var(--body-bg); color: var(--text-primary); }
.glass-table td:first-child { border-left: 1px solid var(--body-bg); border-top-left-radius: 6px; border-bottom-left-radius: 6px; }
.glass-table td:last-child { border-right: 1px solid var(--body-bg); border-top-right-radius: 6px; border-bottom-right-radius: 6px; }

.badge { padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.badge.sale { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.badge.return { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

.actions { display: flex; gap: 6px; }
.btn-icon { width: 28px; height: 28px; border-radius: 6px; background: var(--body-bg); color: var(--text-muted); border: 1px solid var(--header-border); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.btn-icon:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-icon.delete:hover { background: #ef4444; border-color: #ef4444; }

.empty-state { text-align: center; padding: 40px; color: var(--text-muted); font-size: 14px; }
.empty-state i { font-size: 24px; margin-bottom: 10px; display: block; opacity: 0.5; }

.text-muted { color: var(--text-muted) !important; }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(3px); padding: 20px; }
.modal-glass { background: var(--surface); padding: 25px; border-radius: 16px; width: 450px; max-width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: zoomIn 0.2s ease; max-height: 90vh; overflow-y: auto; border: 1px solid var(--header-border); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--header-border); }
.modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.modal-header h3 i { font-size: 15px; }
.modal-large { width: 900px; max-width: 95%; }
.btn-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); transition: color 0.2s; }
.btn-close:hover { color: #ef4444; }
.hidden { display: none !important; }

/* Animations */
@keyframes slideInLeft { from{opacity:0; transform:translateX(-20px);} to{opacity:1; transform:translateX(0);} }
@keyframes slideDown { from{opacity:0; transform:translateY(-10px);} to{opacity:1; transform:translateY(0);} }
@keyframes zoomIn { from{opacity:0; transform:scale(0.95);} to{opacity:1; transform:scale(1);} }
.slide-in-left { animation: slideInLeft 0.4s ease-out; }
.fade-in-row { animation: slideDown 0.3s ease-out; }

/* Pagination */
.pagination-wrapper { margin-top: 20px; display: flex; justify-content: center; }
.pagination-wrapper nav { display: flex; gap: 5px; }
.pagination-wrapper .pagination { display: flex; gap: 5px; list-style: none; padding: 0; margin: 0; }
.pagination-wrapper .page-item { }
.pagination-wrapper .page-link {
    padding: 8px 14px; border-radius: 8px; background: var(--surface); border: 1px solid var(--header-border);
    color: var(--text-primary); font-weight: 600; font-size: 13px; text-decoration: none;
    transition: all 0.2s; display: flex; align-items: center; justify-content: center; min-width: 38px;
}
.pagination-wrapper .page-link:hover { background: var(--primary); color: white; border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2); }
.pagination-wrapper .page-item.active .page-link { background: var(--primary); color: white; border-color: var(--primary); }
.pagination-wrapper .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; pointer-events: none; }

/* Custom Scroll */
.custom-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
.custom-scroll::-webkit-scrollbar-track { background: var(--body-bg); border-radius: 10px; }
.custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.left-panel::-webkit-scrollbar { width: 6px; }
.left-panel::-webkit-scrollbar-track { background: transparent; }
.left-panel::-webkit-scrollbar-thumb { background: var(--header-border); border-radius: 10px; }
.left-panel::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

/* Responsive Design */
@media (max-width: 1400px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 1200px) {
    .split-layout { flex-direction: column; }
    .left-panel { position: relative; top: 0; max-height: none; flex: 1; width: 100%; max-width: 100%; }
    .right-panel { width: 100%; }
}

@media (max-width: 768px) {
    .page-wrapper { width: 100%; margin-left: 0; padding: 15px; }
    .stats-grid { grid-template-columns: 1fr; }
    .toolbar-glass { padding: 10px; }
    .export-group { width: 100%; justify-content: center; }
}
.custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
// --- Alert Helper ---
function showAlert(type, message) {
    const container = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `glass-alert ${type}`;
    alert.innerHTML = `
        <div class="alert-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}</div>
        <button class="btn-close-alert" onclick="this.parentElement.remove()">&times;</button>
    `;
    container.appendChild(alert);
    setTimeout(() => alert.remove(), 4000);
}

function showFormMessage(type, message) {
    const box = document.getElementById('formMessage');
    box.className = `message-box ${type}`;
    box.innerText = message;
    box.style.display = 'block';
    setTimeout(() => { box.style.display = 'none'; }, 4000);
}

// --- Dynamic Total Calculation ---
function calculateTotal() {
    const qty = parseFloat(document.getElementById('stockQty').value) || 0;
    const price = parseFloat(document.getElementById('unitPrice').value) || 0;
    const total = qty * price;
    document.getElementById('totalDisplay').innerText = 'FRW ' + total.toFixed(2);
}

function calculateEditTotal() {
    const qty = parseFloat(document.getElementById('editQty').value) || 0;
    const price = parseFloat(document.getElementById('editUnitPrice').value) || 0;
    const total = qty * price;
    document.getElementById('editTotalDisplay').innerText = 'FRW ' + total.toFixed(2);
}

// --- Product Price Fetch ---
document.getElementById('productSelect').addEventListener('change', function() {
    const pid = this.value;
    if (!pid) {
        document.getElementById('unitPrice').value = '';
        return;
    }
    
    fetch(`/admin/stockout/product-price/${pid}`)
        .then(r => r.json())
        .then(d => {
            document.getElementById('unitPrice').value = d.unit_price || 0;
            calculateTotal();
        })
        .catch(err => console.error('Error fetching price:', err));
});

// --- Customer Modal ---
function openCustomerModal() {
    document.getElementById('customerModal').classList.remove('hidden');
}

function closeCustomerModal() {
    document.getElementById('customerModal').classList.add('hidden');
    document.getElementById('saveCustomerForm').reset();
}

document.getElementById('saveCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    
    fetch("{{ route('admin.stockout.storeCustomer') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: form.name.value,
            email: form.email.value,
            phone: form.phone.value,
            address: form.address.value
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            showAlert('success', 'Customer added successfully!');
            closeCustomerModal();
            
            // Add to dropdown and select
            const option = document.createElement('option');
            option.value = res.customer.id;
            option.text = res.customer.name;
            option.selected = true;
            document.getElementById('customerSelect').appendChild(option);
        } else {
            showAlert('error', res.message || 'Error saving customer');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to save customer');
    });
});

// --- Stock Out Form Submit ---
document.getElementById('stockOutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const productId = document.getElementById('productSelect').value;
    const qty = parseInt(document.getElementById('stockQty').value);
    const type = document.getElementById('typeSelect').value;
    
    // Check stock availability for sales
    if (type === 'sale') {
        fetch(`/admin/stockout/check-stock/${productId}`)
            .then(r => r.json())
            .then(res => {
                if (qty > res.available) {
                    showFormMessage('error', `Insufficient stock! Only ${res.available} units available.`);
                } else {
                    submitStockOut(form);
                }
            })
            .catch(err => {
                console.error('Error checking stock:', err);
                submitStockOut(form);
            });
    } else {
        submitStockOut(form);
    }
});

function submitStockOut(form) {
    fetch("{{ route('admin.stockout.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            customer_id: form.customer_id.value,
            product_id: form.product_id.value,
            quantity: form.quantity.value,
            unit_price: form.unit_price.value,
            type: form.type.value,
            stock_out_date: form.stock_out_date.value,
            note: form.note.value
        })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', 'Stock out transaction saved successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showFormMessage('error', d.message || 'Error saving transaction');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showFormMessage('error', 'Failed to save transaction');
    });
}

// --- Stock Overview Modal ---
function openStockOverviewModal() {
    document.getElementById('stockOverviewModal').classList.remove('hidden');
    const contentDiv = document.getElementById('stockOverviewContent');
    contentDiv.innerHTML = '<p style="text-align: center; padding: 20px; color: var(--text-light);">Loading...</p>';
    
    fetch("{{ route('admin.stockout.stockOverview') }}")
        .then(r => r.json())
        .then(products => {
            contentDiv.innerHTML = `<div class="table-wrapper custom-scroll" style="max-height: 500px;">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Total Stock (Initial)</th>
                            <th>Total Sold / Out</th>
                            <th>Current Stock</th>
                            <th>Unit Price</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${products.map((p, i) => `<tr>
                            <td>${i+1}</td>
                            <td><strong>${p.name}</strong></td>
                            <td>${p.total_in}</td>
                            <td>${p.total_out}</td>
                            <td><strong>${p.current_stock}</strong></td>
                            <td>FRW ${p.unit_price.toFixed(2)}</td>
                            <td><strong>FRW ${p.revenue.toFixed(2)}</strong></td>
                        </tr>`).join('')}
                    </tbody>
                </table>
            </div>`;
        })
        .catch(err => {
            console.error('Error:', err);
            contentDiv.innerHTML = '<p style="color:red; text-align: center; padding: 20px;">Error loading stock overview.</p>';
        });
}

function closeStockOverviewModal() {
    document.getElementById('stockOverviewModal').classList.add('hidden');
}

document.getElementById('stockAvailableBtn').addEventListener('click', openStockOverviewModal);

// --- Daily Transactions ---
// --- Daily Transactions Modal ---
function openDailyTransactionsModal() {
    document.getElementById('dailyTransactionsModal').classList.remove('hidden');
    const contentDiv = document.getElementById('dailyTransactionsContent');
    contentDiv.innerHTML = '<p style="text-align: center; padding: 20px; color: var(--text-light);">Loading today\'s transactions...</p>';
    
    fetch("{{ route('admin.stockout.dailyTransactions') }}")
        .then(r => r.json())
        .then(data => {
            const summary = data.summary;
            const transactions = data.transactions;
            
            contentDiv.innerHTML = `<div>
                <p style="margin: 0 0 15px 0; color: var(--text-light); font-size: 13px; text-align: center;">
                    <strong>${summary.date}</strong>
                </p>
                
                <!-- Summary Cards -->
                <div class="stats-grid" style="margin-bottom: 20px;">
                    <div class="stat-card">
                        <div class="icon-bg blue"><i class="fas fa-shopping-cart"></i></div>
                        <div class="stat-info">
                            <h4>Sales</h4>
                            <span>${summary.total_sales}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-bg green"><i class="fas fa-coins"></i></div>
                        <div class="stat-info">
                            <h4>Revenue</h4>
                            <span>FRW ${summary.revenue.toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-bg orange"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <h4>Cost</h4>
                            <span>FRW ${summary.cost.toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-bg ${summary.profit >= 0 ? 'green' : 'red'}"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-info">
                            <h4>Profit</h4>
                            <span style="color: ${summary.profit >= 0 ? '#10b981' : '#ef4444'}">FRW ${summary.profit.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Transactions Table -->
                <div class="table-wrapper custom-scroll" style="max-height: 400px;">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Time</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${transactions.length > 0 ? transactions.map((t, i) => `<tr>
                                <td>${i+1}</td>
                                <td><strong>${t.time}</strong></td>
                                <td>${t.customer}</td>
                                <td>${t.product}</td>
                                <td><span class="badge ${t.type}">${t.type.charAt(0).toUpperCase() + t.type.slice(1)}</span></td>
                                <td>${t.quantity}</td>
                                <td>FRW ${parseFloat(t.unit_price).toFixed(2)}</td>
                                <td><strong>FRW ${parseFloat(t.total_price).toFixed(2)}</strong></td>
                            </tr>`).join('') : '<tr><td colspan="8" class="empty-state"><i class="fas fa-inbox"></i> No transactions today</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>`;
        })
        .catch(err => {
            console.error('Error:', err);
            contentDiv.innerHTML = '<p style="color:red; text-align: center; padding: 20px;">Error loading daily transactions.</p>';
        });
}

function closeDailyTransactionsModal() {
    document.getElementById('dailyTransactionsModal').classList.add('hidden');
}

document.getElementById('dailyTransactionsBtn').addEventListener('click', openDailyTransactionsModal);


// --- Table Search ---
document.getElementById('tableSearch').addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});

// --- Edit Stock Out ---
function editStockOut(data) {
    document.getElementById('editModal').classList.remove('hidden');
    
    document.getElementById('editId').value = data.id;
    document.getElementById('editQty').value = data.quantity;
    document.getElementById('editUnitPrice').value = data.unit_price;
    document.getElementById('editType').value = data.type;
    document.getElementById('editNote').value = data.note || '';
    
    calculateEditTotal();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editStockOutForm').reset();
}

document.getElementById('editStockOutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const id = form.id.value;
    
    fetch(`/admin/stockout/${id}/update`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            quantity: form.quantity.value,
            unit_price: form.unit_price.value,
            type: form.type.value,
            note: form.note.value
        })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', 'Transaction updated successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', d.message || 'Error updating transaction');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to update transaction');
    });
});

// --- Delete Stock Out ---
function deleteStockOut(id) {
    if (!confirm('Are you sure you want to delete this transaction?')) return;
    
    fetch(`/admin/stockout/${id}/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(() => {
        showAlert('success', 'Transaction deleted successfully!');
        setTimeout(() => location.reload(), 1000);
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to delete transaction');
    });
}

// --- Close modals on outside click ---
window.addEventListener('click', function(e) {
    const customerModal = document.getElementById('customerModal');
    const editModal = document.getElementById('editModal');
    const dailyModal = document.getElementById('dailyTransactionsModal');
    const stockModal = document.getElementById('stockOverviewModal');
    
    if (e.target === customerModal) closeCustomerModal();
    if (e.target === editModal) closeEditModal();
    if (e.target === dailyModal) closeDailyTransactionsModal();
    if (e.target === stockModal) closeStockOverviewModal();
});
</script>
