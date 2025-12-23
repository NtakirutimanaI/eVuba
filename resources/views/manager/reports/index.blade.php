@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Intelligence & Reporting</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Generate institutional-grade reports across all tactical modules</p>
        </div>
    </div>

    <div class="main-grid" style="grid-template-columns: repeat(3, 1fr); gap: 2rem;">
        
        <!-- Stock Acquisition (Stock In) -->
        <div class="pro-card" style="padding: 2rem;">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: rgba(99, 102, 241, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem;">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800;">Acquisition Intelligence</h3>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 0.25rem;">Detailed audit of incoming supply chains and procurement.</p>
                </div>
            </div>

            <form action="{{ route('manager.stockin.export.pdf') }}" method="GET" id="stockInForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                        <input type="date" name="start_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                        <input type="date" name="end_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="action-btn" style="flex: 1; background: #ef4444; color: white;">
                        <i class="fas fa-file-pdf"></i> PDF EXPORT
                    </button>
                    <button type="button" onclick="submitExcel('stockInForm', '{{ route('manager.stockin.export.excel') }}')" class="action-btn" style="flex: 1; background: #22c55e; color: white;">
                        <i class="fas fa-file-excel"></i> EXCEL EXPORT
                    </button>
                </div>
            </form>
        </div>

        <!-- Product Distribution (Stock Out) -->
        <div class="pro-card" style="padding: 2rem;">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.5rem;">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800;">Distribution Intelligence</h3>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 0.25rem;">Audit of market sales, returns, and outgoing inventory flows.</p>
                </div>
            </div>

            <form action="{{ route('manager.stockout.export.pdf') }}" method="GET" id="stockOutForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                        <input type="date" name="start_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                        <input type="date" name="end_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="action-btn" style="flex: 1; background: #ef4444; color: white;">
                        <i class="fas fa-file-pdf"></i> PDF EXPORT
                    </button>
                    <button type="button" onclick="submitExcel('stockOutForm', '{{ route('manager.stockout.export.excel') }}')" class="action-btn" style="flex: 1; background: #22c55e; color: white;">
                        <i class="fas fa-file-excel"></i> EXCEL EXPORT
                    </button>
                </div>
            </form>
        </div>

        <!-- Revenue Ledger (Orders) -->
        <div class="pro-card" style="padding: 2rem;">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.5rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800;">Revenue Ledger</h3>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 0.25rem;">Comprehensive analysis of order volumes, status, and revenue.</p>
                </div>
            </div>

            <form action="{{ route('manager.orders.export.pdf') }}" method="GET" id="ordersForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                        <input type="date" name="start_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                        <input type="date" name="end_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="action-btn" style="flex: 1; background: #ef4444; color: white;">
                        <i class="fas fa-file-pdf"></i> PDF EXPORT
                    </button>
                    <button type="button" onclick="submitExcel('ordersForm', '{{ route('manager.orders.export.excel') }}')" class="action-btn" style="flex: 1; background: #22c55e; color: white;">
                        <i class="fas fa-file-excel"></i> EXCEL EXPORT
                    </button>
                </div>
            </form>
        </div>

        <!-- Operational Engagements (Appointments) -->
        <div class="pro-card" style="padding: 2rem;">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: rgba(14, 165, 233, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #0ea5e9; font-size: 1.5rem;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800;">Operational Pulse</h3>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 0.25rem;">Detailed log of service appointments, agent assignments, and status.</p>
                </div>
            </div>

            <form action="{{ route('manager.appointments.export') }}" method="GET" id="appointmentsForm">
                <input type="hidden" name="type" value="pdf" id="appType">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                        <input type="date" name="start_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                        <input type="date" name="end_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" onclick="document.getElementById('appType').value='pdf'" class="action-btn" style="flex: 1; background: #ef4444; color: white;">
                        <i class="fas fa-file-pdf"></i> PDF EXPORT
                    </button>
                    <button type="submit" onclick="document.getElementById('appType').value='excel'" class="action-btn" style="flex: 1; background: #22c55e; color: white;">
                        <i class="fas fa-file-excel"></i> EXCEL EXPORT
                    </button>
                </div>
            </form>
        </div>

        <!-- Inventory Catalog (Products) -->
        <div class="pro-card" style="padding: 2rem;">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: rgba(168, 85, 247, 0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #a855f7; font-size: 1.5rem;">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800;">Inventory Catalog</h3>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 0.25rem;">Audit of registered product entities and registration lifecycle.</p>
                </div>
            </div>

            <form action="{{ route('manager.products.export.pdf') }}" method="GET" id="productsForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                        <input type="date" name="start_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                        <input type="date" name="end_date" required style="width: 100%; border: 1px solid var(--glass-border); padding: 0.75rem; border-radius: 0.75rem; background: var(--bg-main); color: var(--dark);">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="action-btn" style="flex: 1; background: #ef4444; color: white;">
                        <i class="fas fa-file-pdf"></i> PDF EXPORT
                    </button>
                    <button type="button" onclick="submitExcel('productsForm', '{{ route('manager.products.export.excel') }}')" class="action-btn" style="flex: 1; background: #22c55e; color: white;">
                        <i class="fas fa-file-excel"></i> EXCEL EXPORT
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    function submitExcel(formId, url) {
        const form = document.getElementById(formId);
        const originalAction = form.action;
        form.action = url;
        form.submit();
        form.action = originalAction;
    }
</script>

<style>
    .form-group input:focus {
        border-color: var(--primary) !important;
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
