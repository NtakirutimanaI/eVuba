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
            <h1>Acquisition Intelligence</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Orchestrate procurement and incoming supply chains</p>
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

    <!-- Acquisition Stats -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-file-import"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Entries</div>
                    <div class="value">{{ $stats['total_entries'] }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-history"></i> Lifetime Logs
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Volume</div>
                    <div class="value">{{ number_format($stats['total_quantity']) }}</div>
                    <div class="stat-trend up">
                        Units Received
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Capital Invested</div>
                    <div class="value">FRW {{ number_format($stats['total_investment']) }}</div>
                    <div class="stat-trend up">
                        Procurement Value
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Monthly Activity</div>
                    <div class="value">{{ $stats['this_month'] }}</div>
                    <div class="stat-trend up">
                        Current Cycle
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-grid" style="grid-template-columns: 1fr 350px; gap: 2rem; display: grid;">
        <!-- Procurement Ledger -->
        <div class="catalog-section">
            <div class="pro-card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                    <div class="pro-search" style="flex: 1; max-width: 400px;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="internalSearch" placeholder="Search the ledger..." style="background: none; border: none; width: 100%; focus: outline-none;">
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick="openFormModal('stockInModal')" class="action-btn btn-primary" style="height: 38px;">
                            <i class="fas fa-plus-circle"></i> New Acquisition
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="pro-table" id="procurementLedger">
                        <thead>
                            <tr>
                                <th>Log Ref</th>
                                <th>Product Entity</th>
                                <th>Strategic Source</th>
                                <th>Volume</th>
                                <th>Unit / Total (FRW)</th>
                                <th>Classification</th>
                                <th>Timeline</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockIns as $stock)
                            <tr>
                                <td><span style="color: var(--secondary); font-weight: 600;">#{{ $stock->id }}</span></td>
                                <td>
                                    <div style="font-weight: 700; color: var(--dark);">{{ $stock->product->name ?? 'Deleted SKU' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--secondary);">ID: {{ $stock->product_id }}</div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-id-card-alt" style="color: var(--primary); opacity: 0.6;"></i>
                                        <span>{{ $stock->supplier->name ?? 'Internal / N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 800; color: var(--primary);">{{ $stock->quantity }}</span>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ number_format($stock->unit_cost) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--secondary);">Total: {{ number_format($stock->total_cost) }}</div>
                                </td>
                                <td>
                                    <span class="status-badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">{{ ucfirst($stock->type) }}</span>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $stock->stock_in_date->format('M d, Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--secondary);">By: {{ $stock->user->name ?? 'System' }}</div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button onclick="editStock({{ $stock->id }})" class="action-btn" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('manager.stock_in.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Archive this procurement log?')">
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
                                <td colspan="8">
                                    <div style="text-align: center; padding: 4rem; color: var(--secondary);">
                                        <i class="fas fa-truck-loading" style="font-size: 3rem; opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                                        <h3>Ledger Empty</h3>
                                        <p>No procurement logs found in the selected period.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $stockIns->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- Sidebar Analytics -->
        <div class="sidebar-section">
            <div class="pro-card" style="margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1.5rem; font-size: 1rem; color: var(--dark);"><i class="fas fa-chart-line" style="margin-right: 0.5rem; color: var(--primary);"></i> Supply Pulse</h3>
                <canvas id="pulseChart" height="200"></canvas>
            </div>

            <div class="pro-card">
                <h3 style="margin-bottom: 1.5rem; font-size: 1rem; color: var(--dark);"><i class="fas fa-user-plus" style="margin-right: 0.5rem; color: var(--primary);"></i> Partnerships</h3>
                <p style="font-size: 0.85rem; color: var(--secondary); margin-bottom: 1.5rem;">Register new strategic sourcing partners</p>
                <button onclick="openFormModal('supplierModal')" class="action-btn" style="width: 100%; height: 45px; background: rgba(99, 102, 241, 0.1); color: var(--primary); font-weight: 700;">
                    <i class="fas fa-plus"></i> ADD SUPPLIER
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="stockInModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Log New Acquisition</h3>
            <button onclick="closeFormModal('stockInModal')" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('manager.stock_in.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-cube"></i> Product Entity</label>
                    <select name="product_id" required>
                        <option value="">-- Choose SKU --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-id-card-alt"></i> Strategic Source</label>
                    <select name="supplier_id">
                        <option value="">-- Select Source --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-sort-amount-up"></i> Volume Received</label>
                    <input type="number" name="quantity" required min="1" placeholder="0">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Unit Cost (FRW)</label>
                    <input type="number" name="unit_cost" required step="0.01" placeholder="0.00">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-layer-group"></i> Logic Type</label>
                    <select name="type" required>
                        <option value="purchase">Purchase (New Stock)</option>
                        <option value="return">Return (Customer Return)</option>
                        <option value="production">Production (Internal)</option>
                        <option value="donation">Donation (Inward)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Log Date</label>
                    <input type="date" name="stock_in_date" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Transaction Notes</label>
                <textarea name="note" placeholder="Optional audit details..."></textarea>
            </div>

            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-check-circle"></i> COMMIT TO LEDGER
            </button>
        </form>
    </div>
</div>

<div id="supplierModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Register Strategic Source</h3>
            <button onclick="closeFormModal('supplierModal')" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('manager.suppliers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-building"></i> Enterprise Name</label>
                <input type="text" name="name" required placeholder="Organization Name">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Contact Intel</label>
                    <input type="text" name="contact" placeholder="Phone or Representative">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Core Email</label>
                    <input type="email" name="email" placeholder="official@company.com">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Corporate Address</label>
                <textarea name="address" placeholder="Physical location intel..."></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-plus-circle"></i> REGISTER PARTNERSHIP
            </button>
        </form>
    </div>
</div>

<!-- Dynamic Modal for Edit -->
<div id="dynamicModal" class="glass-modal">
    <div class="modal-content pro-card" id="dynamicModalContent"></div>
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

    .glass-alert { padding: 1rem 1.5rem; border-radius: 1rem; backdrop-filter: blur(10px); margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--glass-border); animation: slideDown 0.4s ease-out; }
    .glass-alert.success { background: rgba(34, 197, 94, 0.1); color: #15803d; border-color: rgba(34, 197, 94, 0.2); }
    .glass-alert.danger { background: rgba(239, 68, 68, 0.1); color: #b91c1c; border-color: rgba(239, 68, 68, 0.2); }
    
    @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function openFormModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeFormModal(id) { document.getElementById(id).style.display = 'none'; }

document.getElementById('internalSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#procurementLedger tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

window.onclick = function(event) {
    if (event.target.classList.contains('glass-modal')) { event.target.style.display = 'none'; }
}

const pulseCtx = document.getElementById('pulseChart').getContext('2d');
const chartData = @json($chartData);

new Chart(pulseCtx, {
    type: 'line',
    data: {
        labels: chartData.map(d => d.date),
        datasets: [{
            label: 'Volume Flow',
            data: chartData.map(d => d.qty),
            borderColor: '#6366f1',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            pointRadius: 4,
            pointBackgroundColor: '#6366f1'
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

const products = @json($products);
const suppliers = @json($suppliers);

function editStock(id) {
    const tr = Array.from(document.querySelectorAll('#procurementLedger tbody tr')).find(r => r.cells[0].innerText.includes(`#${id}`));
    if(!tr) return;

    const prodName = tr.cells[1].querySelector('div:first-child').innerText.trim();
    const supName = tr.cells[2].innerText.trim();
    const qty = tr.cells[3].innerText.trim();
    const unitCost = tr.cells[4].querySelector('div:first-child').innerText.replace(/,/g,'').trim();
    const type = tr.cells[5].innerText.trim().toLowerCase();
    const date = tr.cells[6].querySelector('div:first-child').innerText.trim();

    let prodOptions = '';
    products.forEach(p => { prodOptions += `<option value="${p.id}" ${p.name === prodName ? 'selected' : ''}>${p.name}</option>`; });

    let supOptions = '<option value="">-- Choose Source --</option>';
    suppliers.forEach(s => { supOptions += `<option value="${s.id}" ${s.name === supName ? 'selected' : ''}>${s.name}</option>`; });

    const updateUrl = `{{ url('manager/stock_in') }}/${id}`;

    const content = `
        <div class="modal-header">
            <h3>Modify Procurement Log</h3>
            <button onclick="closeFormModal('dynamicModal')" class="close-btn">&times;</button>
        </div>
        <form action="${updateUrl}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            
            <div class="form-group">
                <label>Product Entity</label>
                <select name="product_id" required>${prodOptions}</select>
            </div>

            <div class="form-group">
                <label>Strategic Source</label>
                <select name="supplier_id">${supOptions}</select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Volume Received</label>
                    <input type="number" name="quantity" value="${qty}" required min="1">
                </div>
                <div class="form-group">
                    <label>Unit Cost (FRW)</label>
                    <input type="number" name="unit_cost" value="${unitCost}" required step="0.01">
                </div>
            </div>

            <div class="form-group">
                <label>Logic Type</label>
                <select name="type" required>
                    <option value="purchase" ${type === 'purchase' ? 'selected' : ''}>Purchase</option>
                    <option value="return" ${type === 'return' ? 'selected' : ''}>Return</option>
                    <option value="production" ${type === 'production' ? 'selected' : ''}>Production</option>
                    <option value="donation" ${type === 'donation' ? 'selected' : ''}>Donation</option>
                </select>
            </div>

            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-sync-alt"></i> UPDATE LEDGER
            </button>
        </form>
    `;
    document.getElementById('dynamicModalContent').innerHTML = content;
    openFormModal('dynamicModal');
}
    function downloadReport(type) {
        const start = document.getElementById('report_start').value;
        const end = document.getElementById('report_end').value;
        if(!start || !end) {
            alert('Please select both start and end date for orchestration audit.');
            return;
        }
        const baseUrl = type === 'pdf' ? "{{ route('manager.stockin.export.pdf') }}" : "{{ route('manager.stockin.export.excel') }}";
        window.location.href = `${baseUrl}?start_date=${start}&end_date=${end}`;
    }
</script>
