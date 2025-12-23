@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Client Intelligence Hub</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Orchestrate and analyze your global customer relationships</p>
        </div>
        <div class="header-actions">
            <button onclick="openFormModal('customerModal')" class="action-btn btn-primary">
                <i class="fas fa-user-plus"></i> Initialize Entity
            </button>
        </div>
    </div>

    <!-- Client Intelligence Widgets -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Entities</div>
                    <div class="value">{{ $stats['total_entities'] }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-check-circle"></i> Active Registry
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-info">
                    <div class="label">New Acquisitions</div>
                    <div class="value">{{ $stats['new_acquisitions'] }}</div>
                    <div class="stat-trend up">
                        Current Fiscal Cycle
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Regional Clusters</div>
                    <div class="value">{{ $stats['active_regions'] }}</div>
                    <div class="stat-trend">
                        Geographic Distribution
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                    <i class="fas fa-pulse"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Active Engagement</div>
                    <div class="value">{{ $stats['recent_activity'] }}</div>
                    <div class="stat-trend">
                        Last 7 Days
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Engagement Registry -->
    <div class="pro-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Engagement Registry</h2>
            <div class="pro-search">
                <i class="fas fa-search"></i>
                <input type="text" id="clientSearch" placeholder="Refine by ID, Name or Email..." style="background: transparent; border: none; outline: none; padding: 0.5rem; width: 320px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="pro-table" id="clientLedger">
                <thead>
                    <tr>
                        <th style="width: 80px;">REF ID</th>
                        <th>Client Entity</th>
                        <th>Communication Intel</th>
                        <th>Regional Context</th>
                        <th>Registry Date</th>
                        <th style="text-align: right;">Orchestration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <span style="color: var(--secondary); font-weight: 700;">#{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 42px; height: 42px; border-radius: 12px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--primary); border: 1px solid var(--glass-border);">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: var(--dark);">{{ $customer->name }}</div>
                                        <div style="font-size: 0.7rem; color: var(--secondary); text-transform: uppercase; letter-spacing: 0.5px;">Premium Partner</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--dark); font-weight: 500;">
                                        <i class="fas fa-envelope" style="font-size: 0.8rem; color: var(--primary);"></i>
                                        {{ $customer->email ?? 'N/A' }}
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--secondary);">
                                        <i class="fas fa-phone" style="font-size: 0.75rem;"></i>
                                        {{ $customer->phone ?? 'Unlisted' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-map-marker-alt" style="color: var(--danger); opacity: 0.7;"></i>
                                    <span style="font-weight: 600;">{{ $customer->address ?? 'Digital Entity' }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700;">{{ $customer->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">{{ $customer->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <button onclick="editCustomer({{ $customer->id }}, '{{ $customer->name }}', '{{ $customer->email }}', '{{ $customer->phone }}', '{{ $customer->address }}')" class="action-btn" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                        <i class="fas fa-fingerprint"></i>
                                    </button>
                                    <form action="{{ route('manager.customer.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Archive this client entity?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: none;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div style="text-align: center; padding: 5rem; color: var(--secondary);">
                                    <i class="fas fa-user-slash" style="font-size: 4rem; opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                                    <h3 style="font-weight: 700;">No Client Entities Detected</h3>
                                    <p>Your institutional registry is currently empty.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Initialization Modal -->
<div id="customerModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Initialize Client Entity</h3>
            <button onclick="closeFormModal('customerModal')" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('manager.customer.store') }}" method="POST" id="customerForm">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-id-card"></i> Formal Entity Name</label>
                <input type="text" name="name" required placeholder="Full Legal Name">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Communication Email</label>
                    <input type="email" name="email" placeholder="client@example.com">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Contact Vector</label>
                    <input type="text" name="phone" placeholder="+250 ...">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Regional Domicile</label>
                <textarea name="address" placeholder="Physical location intel..."></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1.5rem; height: 50px; font-weight: 800;">
                <i class="fas fa-check-circle"></i> COMMIT TO REGISTRY
            </button>
        </form>
    </div>
</div>

<!-- Evolution Modal (Edit) -->
<div id="editCustomerModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Optimize Client Dossier</h3>
            <button onclick="closeFormModal('editCustomerModal')" class="close-btn">&times;</button>
        </div>
        <form id="editCustomerForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Formal Entity Name</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Communication Email</label>
                    <input type="email" name="email" id="edit_email">
                </div>
                <div class="form-group">
                    <label>Contact Vector</label>
                    <input type="text" name="phone" id="edit_phone">
                </div>
            </div>
            <div class="form-group">
                <label>Regional Domicile</label>
                <textarea name="address" id="edit_address"></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1.5rem; height: 50px; font-weight: 800;">
                <i class="fas fa-sync-alt"></i> UPDATE DOSSIER
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
        backdrop-filter: blur(12px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }
    .modal-content { width: 95%; max-width: 550px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem; }
    .modal-header h3 { margin: 0; font-weight: 900; background: linear-gradient(90deg, var(--primary), var(--info)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .close-btn { background: none; border: none; font-size: 1.5rem; color: var(--secondary); cursor: pointer; }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; font-size: 0.8rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-group input, .form-group textarea {
        width: 100%; padding: 0.85rem 1.25rem; background: var(--bg-main); border: 1px solid var(--glass-border); border-radius: 12px; font-size: 0.95rem; color: var(--dark); transition: all 0.3s;
    }
    .form-group input:focus { border-color: var(--primary); background: var(--white); outline: none; box-shadow: var(--shadow-md); }

    .glass-alert { padding: 1.25rem 2rem; border-radius: 16px; backdrop-filter: blur(10px); margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--glass-border); }
    .glass-alert.success { background: rgba(34, 197, 94, 0.1); color: #15803d; border-color: rgba(34, 197, 94, 0.2); }
</style>

<script>
function openFormModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeFormModal(id) { document.getElementById(id).style.display = 'none'; }

function editCustomer(id, name, email, phone, address) {
    document.getElementById('editCustomerForm').action = `/manager/customer/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_address').value = address;
    openFormModal('editCustomerModal');
}

document.getElementById('clientSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#clientLedger tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

window.onclick = function(event) {
    if (event.target.classList.contains('glass-modal')) { event.target.style.display = 'none'; }
}
</script>
