@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <!-- Feedback Notifications -->
    <div id="globalAlert" style="display: none;"></div>

    <div class="pro-header">
        <div>
            <h1>Distribution Intelligence</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Orchestrate sales, returns, and outgoing inventory flow</p>
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
            <button onclick="fetchStockBalance()" class="action-btn" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-weight: 700; border: 1px solid rgba(16, 185, 129, 0.2);">
                <i class="fas fa-balance-scale"></i> BALANCE
            </button>
            <button onclick="openFormModal('stockOutModal')" class="action-btn btn-primary">
                <i class="fas fa-plus-circle"></i> NEW
            </button>
        </div>
    </div>

    <!-- Distribution Stats -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Sales Volume</div>
                    <div class="value">{{ number_format($stats['sold_qty']) }}</div>
                    <div class="stat-trend up">
                        Units Despatched
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Current Balance</div>
                    <div class="value">{{ number_format($stats['current_balance']) }}</div>
                    <div class="stat-trend {{ $stats['current_balance'] < 10 ? 'down' : 'up' }}">
                        {{ $stats['current_balance'] < 10 ? 'Critical Level' : 'Stable Reserve' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Estimated Revenue</div>
                    <div class="value">FRW {{ number_format($stats['estimated_profit']) }}</div>
                    <div class="stat-trend up">
                        Sales Portfolio
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Returns/Loss</div>
                    <div class="value">{{ number_format($stats['returns_volume']) }}</div>
                    <div class="stat-trend down">
                        Inward Returns
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-grid" style="grid-template-columns: 1fr 350px; gap: 2rem; display: grid;">
        <!-- Distribution Ledger -->
        <div class="catalog-section">
            <div class="pro-card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                    <form action="{{ route('manager.stockout.index') }}" method="GET" class="pro-search" style="flex: 1; max-width: 400px; display: flex; align-items: center;">
                        <i class="fas fa-search" style="margin-right: 0.5rem; color: var(--secondary);"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ledger..." style="background: none; border: none; width: 100%; outline: none;">
                    </form>
                    <div style="display: flex; gap: 0.5rem;">
                         <button onclick="window.location.href='{{ route('manager.invoice.create') }}'" class="action-btn" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; font-weight: 700;">
                            <i class="fas fa-file-invoice-dollar"></i> CREATE INVOICE
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="pro-table" id="distributionLedger">
                        <thead>
                            <tr>
                                <th># REF</th>
                                <th>Transaction Partner</th>
                                <th>Product Entity</th>
                                <th>Volume</th>
                                <th>Valuation (FRW)</th>
                                <th>Logic</th>
                                <th>Timeline</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockOuts as $out)
                            <tr data-id="{{ $out->id }}">
                                <td><span style="color: var(--secondary); font-weight: 600;">#{{ $out->id }}</span></td>
                                <td>
                                    <div style="font-weight: 700; color: var(--dark);">{{ $out->customer->name ?? 'Direct Counter' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--secondary);">{{ $out->customer->phone ?? 'No Contact' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $out->product->name ?? 'Unknown SKU' }}</div>
                                </td>
                                <td>
                                    <span style="font-weight: 800; color: var(--primary);">{{ $out->quantity }}</span>
                                </td>
                                <td>
                                    <div style="font-weight: 700;">{{ number_format($out->unit_price * $out->quantity) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--secondary);">@ {{ number_format($out->unit_price) }}</div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $out->type == 'sale' ? 'status-processing' : 'status-cancelled' }}">
                                        {{ ucfirst($out->type) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($out->stock_out_date)->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button onclick="viewStockOut({{ $out->id }})" class="action-btn" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editStockOut({{ $out->id }})" class="action-btn" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteStockOut({{ $out->id }})" class="action-btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 4rem; color: var(--secondary);">
                                    <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                                    <h3>Ledger Empty</h3>
                                    <p>No distribution logs found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $stockOuts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="sidebar-section">
            <!-- Revenue Pulse Chart -->
            <div class="pro-card" style="margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1.5rem; font-size: 1rem; color: var(--dark);"><i class="fas fa-chart-line" style="margin-right: 0.5rem; color: var(--primary);"></i> Revenue Pulse</h3>
                <canvas id="revenuePulseChart" height="200"></canvas>
            </div>

            <!-- Strategic Partner Activation -->
            <div class="pro-card">
                <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--dark);"><i class="fas fa-user-plus" style="margin-right: 0.5rem; color: var(--primary);"></i> Market Hub</h3>
                <p style="font-size: 0.85rem; color: var(--secondary); margin-bottom: 1.5rem;">Onboard new strategic distribution partners</p>
                <button onclick="openFormModal('customerModal')" class="action-btn" style="width: 100%; height: 45px; background: rgba(99, 102, 241, 0.1); color: var(--primary); font-weight: 700;">
                    <i class="fas fa-plus-circle"></i> ADD CUSTOMER
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="stockOutModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Log Distribution Flow</h3>
            <button onclick="closeFormModal('stockOutModal')" class="close-btn">&times;</button>
        </div>
        <form id="stockOutSubmit" onsubmit="handleStockOutSubmit(event)">
            <div id="stockAlert" class="glass-alert danger" style="display: none; margin-bottom: 1rem;">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="stockAlertText">Insufficient stock!</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-user-tie"></i> Strategic Partner</label>
                    <select name="customer_id" id="customerSelect" required>
                        <option value="">-- Select Partner --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-cube"></i> Product SKU</label>
                    <select name="product_id" id="productSelect" required onchange="fetchPrice(this.value)">
                        <option value="">-- Select SKU --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-sort-numeric-up"></i> Flow Volume</label>
                    <input type="number" name="quantity" id="stockQty" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Despatch Valuation (FRW)</label>
                    <input type="number" name="unit_price" id="unitPrice" min="0" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-exchange-alt"></i> Logic Type</label>
                    <select name="type" id="typeSelect">
                        <option value="sale">Market Sale</option>
                        <option value="return">Operational Return</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Distribution Date</label>
                    <input type="date" name="stock_out_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Flow Annotations</label>
                <textarea name="note" placeholder="Strategic notes regarding this despatch..."></textarea>
            </div>

            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-paper-plane"></i> COMMIT TO DISTRIBUTION
            </button>
        </form>
    </div>
</div>

<div id="customerModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Onboard Distribution Partner</h3>
            <button onclick="closeFormModal('customerModal')" class="close-btn">&times;</button>
        </div>
        <form onsubmit="handleCustomerSubmit(event)">
            <div class="form-group">
                <label><i class="fas fa-building"></i> Partner Identification</label>
                <input type="text" name="name" required placeholder="Organization or Full Name">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Core Intel Email</label>
                    <input type="email" name="email" placeholder="official@partner.com">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Contact Vector</label>
                    <input type="text" name="phone" placeholder="+250 ...">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Physical Hub Address</label>
                <textarea name="address" placeholder="Partner's primary logistics hub..."></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-user-check"></i> ACTIVATE PARTNERSHIP
            </button>
        </form>
    </div>
</div>

<div id="dynamicModal" class="glass-modal">
    <div class="modal-content pro-card" id="dynamicModalContent"></div>
</div>

<style>
    .glass-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; }
    .modal-content { width: 95%; max-width: 550px; position: relative; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--glass-border); }
    .modal-header h3 { margin: 0; font-weight: 800; background: linear-gradient(90deg, var(--primary), var(--info)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .close-btn { background: none; border: none; font-size: 1.5rem; color: var(--secondary); cursor: pointer; }

    .form-group { margin-bottom: 1.25rem; }
    .form-group label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.5rem; }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 0.75rem 1rem; background: var(--bg-main); border: 1px solid var(--glass-border); border-radius: 0.75rem; font-size: 0.9rem; color: var(--dark); transition: all 0.2s;
    }
    .form-group input:focus { border-color: var(--primary); background: var(--white); outline: none; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }

    .glass-alert { padding: 1rem 1.5rem; border-radius: 1rem; backdrop-filter: blur(10px); margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--glass-border); animation: slideDown 0.4s ease-out; box-shadow: var(--shadow-sm); }
    .glass-alert.success { background: rgba(34, 197, 94, 0.1); color: #15803d; border-color: rgba(34, 197, 94, 0.2); }
    .glass-alert.danger { background: rgba(239, 68, 68, 0.1); color: #b91c1c; border-color: rgba(239, 68, 68, 0.2); }
    @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function openFormModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeFormModal(id) { document.getElementById(id).style.display = 'none'; }

function showGlobalAlert(type, message) {
    const alert = document.getElementById('globalAlert');
    alert.className = `glass-alert ${type}`;
    alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> <span>${message}</span>`;
    alert.style.display = 'flex';
    setTimeout(() => { alert.style.display = 'none'; }, 5000);
}

window.onclick = function(event) {
    if (event.target.classList.contains('glass-modal')) { event.target.style.display = 'none'; }
}

// Chart Intelligence
const pulseCtx = document.getElementById('revenuePulseChart').getContext('2d');
const chartData = @json($chartData);

new Chart(pulseCtx, {
    type: 'line',
    data: {
        labels: chartData.map(d => d.date),
        datasets: [{
            label: 'Market Revenue',
            data: chartData.map(d => d.revenue),
            borderColor: '#10b981',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            pointRadius: 4,
            pointBackgroundColor: '#10b981'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});

function fetchPrice(pid) {
    if(!pid) { document.getElementById('unitPrice').value = ''; return; }
    fetch(`/manager/stockout/product-price/${pid}`).then(r=>r.json()).then(d=>{
        document.getElementById('unitPrice').value = d.unit_price || 0;
    });
}

function handleCustomerSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const data = {
        name: form.name.value,
        email: form.email.value,
        phone: form.phone.value,
        address: form.address.value
    };

    fetch("{{ route('manager.stockout.storeCustomer') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(r=>r.json()).then(res=>{
        if(res.success) {
            showGlobalAlert('success', 'Partner onboarded successfully!');
            const select = document.getElementById('customerSelect');
            const opt = document.createElement('option');
            opt.value = res.customer.id;
            opt.text = res.customer.name;
            opt.selected = true;
            select.appendChild(opt);
            closeFormModal('customerModal');
        } else {
            showGlobalAlert('danger', res.message || 'Onboarding failed.');
        }
    });
}

function handleStockOutSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const qty = parseInt(document.getElementById('stockQty').value);
    const pid = document.getElementById('productSelect').value;
    const type = document.getElementById('typeSelect').value;

    fetch(`/manager/stockout/check-stock/${pid}`).then(r=>r.json()).then(res=>{
        if(type === 'sale' && qty > res.available) {
            const alert = document.getElementById('stockAlert');
            document.getElementById('stockAlertText').innerText = `Critical Deficit. Available Reserve: ${res.available}`;
            alert.style.display = 'flex';
        } else {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            fetch("{{ route('manager.stockout.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(r=>r.json()).then(d=>{
                if(d.success) {
                    showGlobalAlert('success', 'Distribution flow committed!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showGlobalAlert('danger', d.message || 'Commit failed.');
                }
            });
        }
    });
}

function viewStockOut(id) {
    fetch(`/manager/stockout/${id}/json`).then(r=>r.json()).then(d=>{
        const content = `
            <div class="modal-header">
                <h3>Transaction Intelligence</h3>
                <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
            </div>
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 70px; height: 70px; background: rgba(99, 102, 241, 0.1); border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 1.5rem; border: 1px solid var(--glass-border);">
                    <i class="fas fa-receipt"></i>
                </div>
                <h2 style="font-weight: 800; margin-bottom: 0.5rem;">Log #${d.id}</h2>
                <span class="status-badge ${d.type == 'sale' ? 'status-processing' : 'status-cancelled'}">${d.type.toUpperCase()}</span>
            </div>
            <div class="pro-card" style="background: var(--bg-main); border: 1px solid var(--glass-border); margin-bottom: 1.5rem;">
                <div style="padding: 1rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between;">
                    <span style="color: var(--secondary); font-size: 0.8rem;">PRODUCT ENTITY</span>
                    <span style="font-weight: 700;">${d.product.name}</span>
                </div>
                <div style="padding: 1rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between;">
                    <span style="color: var(--secondary); font-size: 0.8rem;">STRATEGIC PARTNER</span>
                    <span style="font-weight: 700;">${d.customer.name}</span>
                </div>
                <div style="padding: 1rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between;">
                    <span style="color: var(--secondary); font-size: 0.8rem;">FLOW VOLUME</span>
                    <span style="font-weight: 700; color: var(--primary);">${d.quantity} Units</span>
                </div>
                <div style="padding: 1rem; display: flex; justify-content: space-between;">
                    <span style="color: var(--secondary); font-size: 0.8rem;">TOTAL VALUATION</span>
                    <span style="font-weight: 700;">FRW ${new Intl.NumberFormat().format(d.quantity * d.unit_price)}</span>
                </div>
            </div>
            <div class="form-group">
                <label>Flow Annotations</label>
                <div style="padding: 1rem; background: var(--bg-main); border-radius: 0.75rem; color: var(--secondary); font-size: 0.9rem; min-height: 60px;">
                    ${d.note || 'No strategic annotations provided.'}
                </div>
            </div>
        `;
        document.getElementById('dynamicModalContent').innerHTML = content;
        openFormModal('dynamicModal');
    });
}

function editStockOut(id) {
    fetch(`/manager/stockout/${id}/json`).then(r=>r.json()).then(d=>{
        const content = `
            <div class="modal-header">
                <h3>Modify Distribution Log</h3>
                <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
            </div>
            <form id="editStockOutForm" onsubmit="handleEditSubmit(event, ${d.id})">
                <div class="form-group">
                    <label>Product: <strong>${d.product.name}</strong></label>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Flow Volume</label>
                        <input type="number" name="quantity" value="${d.quantity}" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Valuation (FRW)</label>
                        <input type="number" name="unit_price" value="${d.unit_price}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Logic Type</label>
                    <select name="type">
                        <option value="sale" ${d.type == 'sale' ? 'selected' : ''}>Market Sale</option>
                        <option value="return" ${d.type == 'return' ? 'selected' : ''}>Operational Return</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Flow Annotations</label>
                    <textarea name="note">${d.note || ''}</textarea>
                </div>
                <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    <i class="fas fa-sync-alt"></i> UPDATE DISTRIBUTION
                </button>
            </form>
        `;
        document.getElementById('dynamicModalContent').innerHTML = content;
        openFormModal('dynamicModal');
    });
}

function handleEditSubmit(e, id) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    fetch(`/manager/stockout/${id}/update`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(r=>r.json()).then(d=>{
        if(d.success) {
            showGlobalAlert('success', 'Distribution log synchronized!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showGlobalAlert('danger', d.message || 'Update failed.');
        }
    });
}

function deleteStockOut(id) {
    if(!confirm('Are you sure you want to archive this distribution log?')) return;
    fetch(`/manager/stockout/${id}/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(r=>r.json()).then(d=>{
        if(d.success) {
            showGlobalAlert('success', 'Log archived.');
            setTimeout(() => location.reload(), 500);
        }
    });
}
function fetchStockBalance() {
    const modalContent = document.getElementById('dynamicModalContent');
    modalContent.innerHTML = `
        <div class="modal-header">
            <h3>Strategic Stock Balance</h3>
            <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
        </div>
        <div style="text-align: center; padding: 2rem;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
            <p style="margin-top: 1rem; color: var(--secondary);">Syncing inventory levels...</p>
        </div>
    `;
    openFormModal('dynamicModal');

    fetch("{{ route('manager.stockout.stockOverview') }}")
    .then(r => r.json())
    .then(products => {
        let rows = products.map((p, i) => `
            <tr>
                <td><span style="font-weight: 700; color: var(--secondary);">#${i+1}</span></td>
                <td><div style="font-weight: 600;">${p.name}</div></td>
                <td><span style="color: var(--secondary);">${p.total_in}</span></td>
                <td><span style="color: var(--danger); font-weight: 600;">${p.total_out}</span></td>
                <td><span class="status-badge ${p.current_stock < 5 ? 'status-cancelled' : 'status-processing'}" style="font-weight: 800;">${p.current_stock}</span></td>
                <td style="text-align: right; font-weight: 700;">${new Intl.NumberFormat().format(p.revenue)}</td>
            </tr>
        `).join('');

        modalContent.innerHTML = `
            <div class="modal-header">
                <h3>Strategic Stock Balance</h3>
                <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
            </div>
            <div class="table-responsive" style="max-height: 450px;">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Entity</th>
                            <th>Total In</th>
                            <th>Total Out</th>
                            <th>Current</th>
                            <th style="text-align: right;">Revenue (FRW)</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
            <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(99, 102, 241, 0.05); border-radius: 0.75rem; font-size: 0.8rem; color: var(--secondary);">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i> Revenue is calculated based on cumulative sales at weighted average valuation.
            </div>
        `;
    });
}
function downloadReport(type) {
    const start = document.getElementById('report_start').value;
    const end = document.getElementById('report_end').value;
    if(!start || !end) {
        alert('Please select both start and end date for distribution audit.');
        return;
    }
    const baseUrl = type === 'pdf' ? "{{ route('manager.stockout.export.pdf') }}" : "{{ route('manager.stockout.export.excel') }}";
    window.location.href = `${baseUrl}?start_date=${start}&end_date=${end}`;
}
</script>
