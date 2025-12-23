@include('layouts.header')
@include('layouts.sidebar')

<div class="announcement-mgmt-container">
    <div class="mgmt-header glass-panel">
        <div class="header-content">
            <h1><i class="fas fa-bullhorn text-indigo"></i> Announcement Management</h1>
            <p>Broadcast intelligence and updates across the organization.</p>
        </div>
        <button class="glass-btn primary" onclick="openCreateModal()">
            <i class="fas fa-plus"></i> New Announcement
        </button>
    </div>

    <div class="mgmt-body glass-panel">
        <div class="table-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="annSearch" placeholder="Search announcements..." onkeyup="filterAnnouncements()">
            </div>
        </div>

        <div class="modern-table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Message Preview</th>
                        <th>Target Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="annTableBody">
                    @forelse($announcements as $ann)
                    <tr>
                        <td><strong>{{ $ann->title }}</strong></td>
                        <td><span class="text-muted">{{ Str::limit($ann->message, 60) }}</span></td>
                        <td>
                            <span class="role-badge {{ $ann->target_role ?: 'all' }}">
                                {{ $ann->target_role ? ucfirst($ann->target_role) : 'Everyone' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.announcements.toggle', $ann->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="status-toggle {{ $ann->is_active ? 'active' : 'inactive' }}">
                                    {{ $ann->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>{{ $ann->created_at->format('M d, Y') }}</td>
                        <td class="action-cell">
                            <button class="icon-btn edit" onclick="editAnnouncement({{ $ann->id }}, '{{ addslashes($ann->title) }}', '{{ addslashes($ann->message) }}', '{{ $ann->target_role }}', {{ $ann->is_active }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this announcement?')">
                                @csrf @method('DELETE')
                                <button class="icon-btn delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-row">No announcements found. Create your first broadcast above.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-footer">
            {{ $announcements->links() }}
        </div>
    </div>
</div>

<!-- CREATE/EDIT MODAL -->
<div id="annModal" class="modal">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h2 id="modalTitle">Broadcast Intelligence</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="annForm" action="{{ route('admin.announcements.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="form-group">
                <label>Announcement Title</label>
                <input type="text" name="title" id="titleInput" placeholder="Headline for the update" required>
            </div>

            <div class="form-group">
                <label>Intelligence Payload (Message)</label>
                <textarea name="message" id="messageInput" rows="5" placeholder="Detailed content of your broadcast..." required></textarea>
            </div>

            <div class="form-flex">
                <div class="form-group flex-1">
                    <label>Target Segment</label>
                    <select name="target_role" id="roleInput">
                        <option value="">Everyone</option>
                        <option value="admin">Administrators</option>
                        <option value="manager">Managers</option>
                        <option value="employee">Employees</option>
                        <option value="customer">Customers</option>
                    </select>
                </div>
                <div class="form-group status-group">
                    <label>Status</label>
                    <div class="toggle-switch">
                        <input type="checkbox" name="is_active" id="statusInput" value="1" checked>
                        <span class="slider"></span>
                        <span class="label">Immediate Broadcast</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="glass-btn secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="glass-btn primary">Execute Broadcast</button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --indigo: #6366f1;
    --indigo-glow: rgba(99, 102, 241, 0.2);
    --slate: #0f172a;
    --text-muted: #64748b;
    --glass-bg: rgba(255, 255, 255, 0.95);
    --glass-border: rgba(226, 232, 240, 0.8);
}

.announcement-mgmt-container {
    margin-left: 250px;
    padding: 30px;
    background: #f8fafc;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
}

.glass-panel {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    backdrop-filter: blur(10px);
}

.mgmt-header {
    padding: 25px 35px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h1 { margin: 0; font-size: 24px; font-weight: 800; color: var(--slate); }
.header-content p { margin: 5px 0 0 0; color: var(--text-muted); font-size: 14px; }

.mgmt-body { padding: 0; overflow: hidden; }

.table-actions { padding: 20px 30px; border-bottom: 1px solid #f1f5f9; background: #fff; }
.search-box { position: relative; max-width: 400px; }
.search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-box input { 
    width: 100%; padding: 10px 15px 10px 40px; border-radius: 12px; 
    border: 1.5px solid #e2e8f0; font-size: 14px; outline: none; transition: all 0.2s;
}
.search-box input:focus { border-color: var(--indigo); box-shadow: 0 0 0 4px var(--indigo-glow); }

.modern-table-wrapper { overflow-x: auto; }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { 
    padding: 15px 30px; text-align: left; background: #fafbfc; 
    font-size: 12px; text-transform: uppercase; color: var(--text-muted); 
    font-weight: 700; border-bottom: 2px solid #f1f5f9;
}
.modern-table td { padding: 18px 30px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: var(--slate); }

.role-badge { padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.role-badge.all { background: #e0f2fe; color: #0ea5e9; }
.role-badge.admin { background: #fee2e2; color: #ef4444; }
.role-badge.manager { background: #fef3c7; color: #d97706; }
.role-badge.employee { background: #dcfce7; color: #10b981; }
.role-badge.customer { background: #eef2ff; color: #6366f1; }

.status-toggle { 
    border: none; padding: 5px 12px; border-radius: 10px; font-size: 11px; 
    font-weight: 800; cursor: pointer; transition: all 0.2s;
}
.status-toggle.active { background: #dcfce7; color: #166534; }
.status-toggle.inactive { background: #f1f5f9; color: #64748b; }

.icon-btn { border: none; width: 34px; height: 34px; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-size: 13px; }
.icon-btn.edit { background: #e0f2fe; color: #0ea5e9; }
.icon-btn.delete { background: #fee2e2; color: #ef4444; }
.icon-btn:hover { transform: scale(1.1); }

.empty-row { text-align: center; padding: 60px !important; color: var(--text-muted); font-style: italic; }

.modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(5px); z-index: 2000; align-items: center; justify-content: center; }
.modal-content { width: 95%; max-width: 600px; padding: 0; box-shadow: 0 40px 80px -20px rgba(0,0,0,0.3); }
.modal-header { padding: 25px 35px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
.modal-header h2 { margin: 0; font-size: 20px; font-weight: 800; }
.close-btn { background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer; }

#annForm { padding: 30px 35px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 13px; color: var(--text-muted); text-transform: uppercase; }
.form-group input, .form-group textarea, .form-group select { 
    width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e2e8f0; 
    font-size: 14px; outline: none; transition: all 0.2s;
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: var(--indigo); box-shadow: 0 0 0 4px var(--indigo-glow); }

.form-flex { display: flex; gap: 20px; }
.flex-1 { flex: 1; }

.toggle-switch { display: flex; align-items: center; gap: 12px; cursor: pointer; }
.toggle-switch .label { font-size: 13px; font-weight: 600; color: var(--slate); }

.glass-btn { 
    padding: 10px 22px; border-radius: 12px; font-weight: 700; font-size: 14px; 
    cursor: pointer; transition: all 0.2s; border: 1.5px solid transparent; display: flex; align-items: center; gap: 8px;
}
.glass-btn.primary { background: var(--indigo); color: #fff; box-shadow: 0 4px 12px var(--indigo-glow); }
.glass-btn.secondary { background: #fff; border-color: #e2e8f0; color: var(--text-muted); }
.glass-btn:hover { transform: translateY(-2px); }

.modal-footer { margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px; }

.pagination-footer { padding: 20px 30px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; }
</style>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = 'Broadcast Intelligence';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('annForm').action = "{{ route('admin.announcements.store') }}";
    document.getElementById('titleInput').value = '';
    document.getElementById('messageInput').value = '';
    document.getElementById('roleInput').value = '';
    document.getElementById('statusInput').checked = true;
    document.getElementById('annModal').style.display = 'flex';
}

function editAnnouncement(id, title, message, role, status) {
    document.getElementById('modalTitle').innerText = 'Update Intelligence';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('annForm').action = "/admin/announcements/" + id;
    document.getElementById('titleInput').value = title;
    document.getElementById('messageInput').value = message;
    document.getElementById('roleInput').value = role || '';
    document.getElementById('statusInput').checked = status;
    document.getElementById('annModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('annModal').style.display = 'none';
}

function filterAnnouncements() {
    let input = document.getElementById('annSearch').value.toLowerCase();
    let rows = document.querySelectorAll('#annTableBody tr:not(.empty-row)');
    
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}
</script>
