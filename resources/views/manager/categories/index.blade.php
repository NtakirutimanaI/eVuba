@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Classification Intelligence</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Orchestrate your inventory hierarchy and taxonomy</p>
        </div>
        <div class="header-actions">
            <button onclick="openFormModal('categoryModal')" class="action-btn btn-primary">
                <i class="fas fa-folder-plus"></i> Establish Category
            </button>
        </div>
    </div>

    <!-- Category Intelligence Stats -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Clusters</div>
                    <div class="value">{{ $categories->count() }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-check-circle"></i> Defined Schema
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
                    <div class="label">Item Density</div>
                    <div class="value">{{ $categories->sum('products_count') }}</div>
                    <div class="stat-trend">
                        Mapped SKU Entities
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Largest Cluster</div>
                    <div class="value">{{ $categories->max('products_count') ?? 0 }}</div>
                    <div class="stat-trend">
                        {{ $categories->sortByDesc('products_count')->first()->name ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Taxonomy Depth</div>
                    <div class="value">Primary</div>
                    <div class="stat-trend">
                        Flat Architecture
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

    <!-- Classification Ledger -->
    <div class="pro-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Classification Ledger</h2>
            <div class="pro-search">
                <i class="fas fa-search"></i>
                <input type="text" id="categorySearch" placeholder="Search categories..." style="background: transparent; border: none; outline: none; padding: 0.5rem; width: 300px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="pro-table" id="categoryTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">REF</th>
                        <th>Classification Entity</th>
                        <th>Definition / Strategy</th>
                        <th>Entity Density</th>
                        <th style="text-align: right;">Orchestration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <span style="color: var(--secondary); font-weight: 700;">#{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 12px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid var(--glass-border);">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <div style="font-weight: 800; color: var(--dark); font-size: 1.05rem;">{{ $category->name }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="color: var(--secondary); font-size: 0.9rem; line-height: 1.4;">
                                    {{ $category->description ?? 'No formal definition provided for this classification cluster.' }}
                                </div>
                            </td>
                            <td>
                                <div style="display: inline-flex; align-items: center; gap: 0.75rem; background: var(--bg-main); padding: 0.4rem 0.85rem; border-radius: 10px; border: 1px solid var(--glass-border);">
                                    <i class="fas fa-box" style="font-size: 0.8rem; color: var(--primary);"></i>
                                    <span style="font-weight: 800; color: var(--dark);">{{ $category->products_count }}</span>
                                    <span style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase;">SKUs</span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')" class="action-btn" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('manager.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Archive this classification cluster? Association logic will be lost.');" style="margin:0;">
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
                            <td colspan="5">
                                <div style="text-align: center; padding: 5rem; color: var(--secondary);">
                                    <i class="fas fa-folder-minus" style="font-size: 4rem; opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                                    <h3 style="font-weight: 700;">No Classifications Detected</h3>
                                    <p>Initialize a category cluster to begin inventory mapping.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Establish Category Cluster</h3>
            <button onclick="closeFormModal('categoryModal')" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('manager.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Classification Name</label>
                <input type="text" name="name" required placeholder="General Inventory Cluster">
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Formal Definition</label>
                <textarea name="description" rows="4" placeholder="Define the scope of this classification..."></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1.5rem; height: 50px; font-weight: 800;">
                <i class="fas fa-check-circle"></i> COMMIT CLUSTER
            </button>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="glass-modal">
    <div class="modal-content pro-card">
        <div class="modal-header">
            <h3>Optimize Classification</h3>
            <button onclick="closeFormModal('editCategoryModal')" class="close-btn">&times;</button>
        </div>
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Classification Name</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Formal Definition</label>
                <textarea name="description" id="edit_description" rows="4"></textarea>
            </div>
            <button type="submit" class="action-btn btn-primary" style="width: 100%; margin-top: 1.5rem; height: 50px; font-weight: 800;">
                <i class="fas fa-sync-alt"></i> UPDATE TAXONOMY
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
    .modal-content { width: 95%; max-width: 500px; }
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

function editCategory(id, name, description) {
    document.getElementById('editCategoryForm').action = `/manager/categories/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    openFormModal('editCategoryModal');
}

document.getElementById('categorySearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#categoryTable tbody tr');
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

window.onclick = function(event) {
    if (event.target.classList.contains('glass-modal')) { event.target.style.display = 'none'; }
}
</script>
