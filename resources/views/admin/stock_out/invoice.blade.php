@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-wrapper main-content">
    
    <!-- Success/Error Feedback -->
    <div id="alertContainer"></div>
    
    <div class="invoice-container">
        
        <!-- Page Header -->
        <div class="page-title-section">
            <h2><i class="fas fa-file-invoice-dollar"></i> Create Invoice</h2>
            <p class="subtitle">Generate professional invoices with payment processing</p>
        </div>

        <!-- Invoice Form Card -->
        <div class="glass-card invoice-card">
            
            <!-- Step 1: Customer Selection -->
            <div class="invoice-section">
                <div class="section-header">
                    <h3><i class="fas fa-user-circle"></i> Step 1: Select Customer</h3>
                </div>
                
                <div class="customer-selection">
                    <div class="form-row">
                        <div class="form-group flex-grow">
                            <label>Customer <span class="req">*</span></label>
                            <select id="customerSelect" required>
                                <option value="">-- Choose Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        data-name="{{ $customer->name }}"
                                        data-email="{{ $customer->email ?? '' }}"
                                        data-phone="{{ $customer->phone ?? '' }}"
                                        data-address="{{ $customer->address ?? '' }}">
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn-add-customer" onclick="openCustomerModal()">
                            <i class="fas fa-user-plus"></i> New Customer
                        </button>
                    </div>
                    
                    <div id="customerDetails" class="customer-card hidden">
                        <div class="customer-info">
                            <h4 id="displayCustomerName"></h4>
                            <p id="displayCustomerEmail"></p>
                            <p id="displayCustomerPhone"></p>
                            <p id="displayCustomerAddress"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider-line"></div>

            <!-- Step 2: Add Products -->
            <div class="invoice-section">
                <div class="section-header">
                    <h3><i class="fas fa-shopping-cart"></i> Step 2: Add Products</h3>
                    <button class="btn-add-item" onclick="addInvoiceItem()">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </button>
                </div>

                <div class="products-table-wrapper">
                    <table class="products-table" id="invoiceItemsTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Product</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="20%">Total</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody">
                            <tr class="empty-state-row">
                                <td colspan="6" class="text-center">
                                    <i class="fas fa-box-open"></i>
                                    <p>No products added yet. Click "Add Product" to start.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="divider-line"></div>

            <!-- Step 3: Invoice Summary -->
            <div class="invoice-section">
                <div class="section-header">
                    <h3><i class="fas fa-calculator"></i> Step 3: Invoice Summary</h3>
                </div>

                <div class="summary-container">
                    <div class="summary-left">
                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea id="invoiceNotes" rows="4" placeholder="Payment terms, delivery instructions, etc..."></textarea>
                        </div>
                    </div>
                    
                    <div class="summary-right">
                        <div class="calculation-box">
                            <div class="calc-row">
                                <span>Subtotal:</span>
                                <strong id="displaySubtotal">FRW 0.00</strong>
                            </div>
                            <div class="calc-row">
                                <span>Tax (18%):</span>
                                <strong id="displayTax">FRW 0.00</strong>
                            </div>
                            <div class="calc-row total-row">
                                <span>TOTAL AMOUNT:</span>
                                <strong id="displayTotal">FRW 0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="invoice-actions">
                <button class="btn-cancel" onclick="window.location.href='{{ route('admin.stockout.index') }}'">
                    <i class="fas fa-times-circle"></i> Cancel
                </button>
                <button class="btn-payment" onclick="processPayment()" id="btnProcessPayment">
                    <i class="fas fa-credit-card"></i> Process Payment
                </button>
                <button class="btn-save-download" onclick="saveAndDownloadInvoice()" id="btnSaveDownload" disabled>
                    <i class="fas fa-file-download"></i> Save & Download Invoice
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Customer Modal -->
<div id="customerModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Add New Customer</h3>
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
                <label>Phone <span class="req">*</span></label>
                <input type="text" name="phone" required placeholder="+250 XXX XXX XXX">
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

<!-- Payment Modal -->
<div id="paymentModal" class="modal-overlay hidden">
    <div class="modal-glass modal-medium">
        <div class="modal-header">
            <h3><i class="fas fa-credit-card"></i> Process Payment</h3>
            <button class="btn-close" onclick="closePaymentModal()">&times;</button>
        </div>
        
        <div class="payment-summary">
            <div class="payment-amount">
                <span>Amount to Pay:</span>
                <strong id="paymentAmount">FRW 0.00</strong>
            </div>
        </div>

        <form id="paymentForm">
            <div class="form-group">
                <label>Payment Method <span class="req">*</span></label>
                <select id="paymentMethod" required>
                    <option value="">-- Select Payment Method --</option>
                    <option value="cash">Cash</option>
                    <option value="mtn_momo">MTN Mobile Money</option>
                    <option value="airtel_money">Airtel Money</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="credit_card">Credit/Debit Card</option>
                </select>
            </div>

            <div id="mobileMoneyFields" class="payment-fields hidden">
                <div class="form-group">
                    <label>Mobile Money Number <span class="req">*</span></label>
                    <input type="text" id="momoNumber" placeholder="+250 7XX XXX XXX">
                </div>
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>You will receive a prompt on your phone to confirm the payment.</p>
                </div>
            </div>

            <div id="bankTransferFields" class="payment-fields hidden">
                <div class="form-group">
                    <label>Bank Name <span class="req">*</span></label>
                    <select id="bankName">
                        <option value="">-- Select Bank --</option>
                        <option value="bank_of_kigali">Bank of Kigali</option>
                        <option value="equity_bank">Equity Bank</option>
                        <option value="i&m_bank">I&M Bank</option>
                        <option value="cogebanque">Cogebanque</option>
                        <option value="access_bank">Access Bank</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Reference Number <span class="req">*</span></label>
                    <input type="text" id="bankReference" placeholder="Transaction reference">
                </div>
            </div>

            <div id="cardFields" class="payment-fields hidden">
                <div class="form-group">
                    <label>Card Number <span class="req">*</span></label>
                    <input type="text" id="cardNumber" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date <span class="req">*</span></label>
                        <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label>CVV <span class="req">*</span></label>
                        <input type="text" id="cardCVV" placeholder="XXX" maxlength="3">
                    </div>
                </div>
            </div>

            <div id="cashFields" class="payment-fields hidden">
                <div class="form-group">
                    <label>Amount Received <span class="req">*</span></label>
                    <input type="number" id="cashReceived" step="0.01" min="0" placeholder="0.00" onchange="calculateChange()">
                </div>
                <div class="form-group">
                    <label>Change</label>
                    <input type="text" id="cashChange" readonly placeholder="FRW 0.00">
                </div>
            </div>

            <button type="submit" class="btn-primary-block">
                <i class="fas fa-check-circle"></i> Confirm Payment
            </button>
        </form>
    </div>
</div>

<style>
/* --- THEME --- */
:root {
    --primary: #4f46e5; --primary-dark: #4338ca;
    --bg: #f8fafc; --text-main: #334155; --text-light: #64748b;
    --glass-bg: rgba(255, 255, 255, 0.95); --glass-border: 1px solid rgba(226, 232, 240, 0.8);
    --danger: #ef4444; --success: #10b981; --warning: #f59e0b; --blue: #3b82f6;
}

body { background: var(--bg); font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-main); margin: 0; }
.page-wrapper { width: 80% !important; margin-left: 222px !important; padding: 25px; min-height: 100vh; box-sizing: border-box; }

/* Alerts */
#alertContainer { position: fixed; top: 80px; right: 25px; z-index: 10000; max-width: 400px; }
.glass-alert {
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center;
    animation: slideInRight 0.3s ease; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.glass-alert.success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; border-left: 4px solid #10b981; }
.glass-alert.error { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; border-left: 4px solid #ef4444; }
.alert-content { display: flex; align-items: center; gap: 10px; font-weight: 600; }
.btn-close-alert { background: none; border: none; font-size: 18px; cursor: pointer; color: inherit; opacity: 0.7; }
.btn-close-alert:hover { opacity: 1; }

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Page Title */
.page-title-section { margin-bottom: 20px; }
.page-title-section h2 { margin: 0 0 5px 0; font-size: 24px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 10px; }
.page-title-section h2 i { color: var(--primary); }
.page-title-section .subtitle { margin: 0; color: var(--text-light); font-size: 14px; }

/* Invoice Container */
.invoice-container { max-width: 1200px; margin: 0 auto; }

/* Glass Card */
.glass-card { background: var(--glass-bg); border: var(--glass-border); border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px; }

/* Invoice Sections */
.invoice-section { margin-bottom: 25px; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.section-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 10px; }
.section-header h3 i { color: var(--primary); font-size: 15px; }

/* Divider */
.divider-line { height: 2px; background: linear-gradient(to right, transparent, #e2e8f0, transparent); margin: 30px 0; }

/* Form Elements */
.form-row { display: flex; gap: 15px; align-items: flex-end; }
.form-group { margin-bottom: 15px; }
.form-group.flex-grow { flex: 1; }
.form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--text-light); margin-bottom: 6px; }
.req { color: var(--danger); }

input, select, textarea {
    width: 100%; box-sizing: border-box; padding: 10px 12px;
    border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;
    font-size: 13px; color: var(--text-main); outline: none; transition: all 0.2s; font-family: inherit;
}
input:focus, select:focus, textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
textarea { resize: vertical; }

/* Customer Selection */
.customer-selection { }
.btn-add-customer {
    padding: 10px 20px; background: var(--success); color: white; border: none; border-radius: 8px;
    font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.2s; white-space: nowrap;
}
.btn-add-customer:hover { background: #059669; transform: translateY(-1px); }

.customer-card {
    margin-top: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}
.customer-info h4 { margin: 0 0 10px 0; font-size: 18px; font-weight: 700; }
.customer-info p { margin: 5px 0; font-size: 13px; opacity: 0.95; display: flex; align-items: center; gap: 8px; }

/* Products Table */
.products-table-wrapper { overflow-x: auto; }
.products-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
.products-table thead th {
    text-align: left; padding: 12px; background: #f8fafc; color: var(--text-light);
    font-weight: 600; text-transform: uppercase; font-size: 11px; border-radius: 8px;
}
.products-table tbody tr { background: white; transition: all 0.2s; }
.products-table tbody tr:not(.empty-state-row) { box-shadow: 0 2px 4px rgba(0,0,0,0.03); }
.products-table tbody tr:not(.empty-state-row):hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-1px); }
.products-table td { padding: 12px; border-top: 1px solid #f8fafc; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
.products-table td:first-child { border-left: 1px solid #f8fafc; border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
.products-table td:last-child { border-right: 1px solid #f8fafc; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

.empty-state-row td { text-align: center; padding: 40px 20px; color: var(--text-light); }
.empty-state-row i { font-size: 48px; opacity: 0.3; display: block; margin-bottom: 10px; }
.empty-state-row p { margin: 0; font-size: 14px; }

.products-table input, .products-table select { padding: 8px 10px; font-size: 12px; margin: 0; }
.item-total { font-weight: 700; color: var(--primary); font-size: 14px; }
.item-number { font-weight: 700; color: var(--text-light); }

.btn-add-item {
    padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 8px;
    font-weight: 600; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px;
    transition: all 0.2s;
}
.btn-add-item:hover { background: var(--primary-dark); transform: translateY(-1px); }

.btn-remove-item {
    padding: 6px 12px; background: #fee2e2; color: #ef4444; border: none; border-radius: 6px;
    font-size: 11px; cursor: pointer; transition: all 0.2s; font-weight: 600;
}
.btn-remove-item:hover { background: #ef4444; color: white; }

/* Summary */
.summary-container { display: flex; gap: 30px; }
.summary-left { flex: 1; }
.summary-right { flex: 0 0 350px; }

.calculation-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}
.calc-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.2); }
.calc-row:last-child { border-bottom: none; }
.calc-row span { font-size: 14px; opacity: 0.9; }
.calc-row strong { font-size: 16px; }
.total-row { border-top: 2px solid rgba(255,255,255,0.4); margin-top: 10px; padding-top: 15px !important; }
.total-row span { font-size: 16px; font-weight: 700; opacity: 1; }
.total-row strong { font-size: 24px; }

/* Action Buttons */
.invoice-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px; }
.invoice-actions button {
    padding: 14px 28px; border: none; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.2s;
}
.btn-cancel { background: #f1f5f9; color: var(--text-main); }
.btn-cancel:hover { background: #e2e8f0; }
.btn-payment { background: var(--warning); color: white; }
.btn-payment:hover { background: #d97706; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }
.btn-save-download { background: var(--success); color: white; }
.btn-save-download:hover:not(:disabled) { background: #059669; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
.btn-save-download:disabled { opacity: 0.5; cursor: not-allowed; }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px); padding: 20px; }
.modal-glass { background: white; padding: 25px; border-radius: 16px; width: 450px; max-width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: zoomIn 0.2s ease; max-height: 90vh; overflow-y: auto; }
.modal-medium { width: 600px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9; }
.modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }
.btn-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-light); transition: color 0.2s; }
.btn-close:hover { color: var(--danger); }
.hidden { display: none !important; }

.btn-primary-block { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; font-size: 14px; }
.btn-primary-block:hover { background: var(--primary-dark); }

@keyframes zoomIn { from{opacity:0; transform:scale(0.95);} to{opacity:1; transform:scale(1);} }

/* Payment Modal */
.payment-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center; color: white;
}
.payment-amount { display: flex; justify-content: space-between; align-items: center; }
.payment-amount span { font-size: 14px; opacity: 0.9; }
.payment-amount strong { font-size: 24px; font-weight: 700; }

.payment-fields { margin-top: 15px; }
.info-box {
    background: #e0e7ff; border-left: 3px solid var(--primary); padding: 12px; border-radius: 6px;
    display: flex; gap: 10px; align-items: flex-start; margin-top: 10px;
}
.info-box i { color: var(--primary); margin-top: 2px; }
.info-box p { margin: 0; font-size: 12px; color: var(--text-main); }

.text-center { text-align: center; }

/* Responsive */
@media (max-width: 768px) {
    .page-wrapper { width: 100%; margin-left: 0; padding: 15px; }
    .form-row { flex-direction: column; align-items: stretch; }
    .btn-add-customer { width: 100%; justify-content: center; }
    .summary-container { flex-direction: column; }
    .summary-right { flex: 1; }
    .invoice-actions { flex-direction: column; }
    .invoice-actions button { width: 100%; justify-content: center; }
}
</style>

<script>
let invoiceItems = [];
let itemCounter = 0;
let paymentProcessed = false;

// Alert Helper
function showAlert(type, message) {
    const container = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `glass-alert ${type}`;
    alert.innerHTML = `
        <div class="alert-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}</div>
        <button class="btn-close-alert" onclick="this.parentElement.remove()">&times;</button>
    `;
    container.appendChild(alert);
    setTimeout(() => alert.remove(), 5000);
}

// Customer Selection
document.getElementById('customerSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const detailsDiv = document.getElementById('customerDetails');
    
    if (this.value) {
        document.getElementById('displayCustomerName').textContent = selected.dataset.name;
        document.getElementById('displayCustomerEmail').innerHTML = selected.dataset.email ? `<i class="fas fa-envelope"></i> ${selected.dataset.email}` : '';
        document.getElementById('displayCustomerPhone').innerHTML = selected.dataset.phone ? `<i class="fas fa-phone"></i> ${selected.dataset.phone}` : '';
        document.getElementById('displayCustomerAddress').innerHTML = selected.dataset.address ? `<i class="fas fa-map-marker-alt"></i> ${selected.dataset.address}` : '';
        detailsDiv.classList.remove('hidden');
    } else {
        detailsDiv.classList.add('hidden');
    }
});

// Customer Modal
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
            
            const option = document.createElement('option');
            option.value = res.customer.id;
            option.text = res.customer.name;
            option.dataset.name = res.customer.name;
            option.dataset.email = res.customer.email || '';
            option.dataset.phone = res.customer.phone || '';
            option.dataset.address = res.customer.address || '';
            option.selected = true;
            document.getElementById('customerSelect').appendChild(option);
            
            document.getElementById('customerSelect').dispatchEvent(new Event('change'));
        } else {
            showAlert('error', res.message || 'Error saving customer');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to save customer');
    });
});

// Add Invoice Item
function addInvoiceItem() {
    // Remove empty state if exists
    const emptyRow = document.querySelector('.empty-state-row');
    if (emptyRow) emptyRow.remove();
    
    itemCounter++;
    const tbody = document.getElementById('invoiceItemsBody');
    const row = document.createElement('tr');
    row.id = `item-row-${itemCounter}`;
    row.innerHTML = `
        <td class="item-number">${itemCounter}</td>
        <td>
            <select class="item-product" data-item-id="${itemCounter}" onchange="updateItemPrice(${itemCounter})" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="item-quantity" data-item-id="${itemCounter}" min="1" value="1" onchange="calculateItemTotal(${itemCounter})" required>
        </td>
        <td>
            <input type="number" class="item-price" data-item-id="${itemCounter}" step="0.01" min="0" value="0" onchange="calculateItemTotal(${itemCounter})" required>
        </td>
        <td>
            <span class="item-total" id="item-total-${itemCounter}">FRW 0.00</span>
        </td>
        <td>
            <button class="btn-remove-item" onclick="removeInvoiceItem(${itemCounter})">
                <i class="fas fa-trash"></i> Remove
            </button>
        </td>
    `;
    tbody.appendChild(row);
}

// Update Item Price from Product
function updateItemPrice(itemId) {
    const select = document.querySelector(`.item-product[data-item-id="${itemId}"]`);
    const productId = select.value;
    
    if (!productId) {
        document.querySelector(`.item-price[data-item-id="${itemId}"]`).value = 0;
        calculateItemTotal(itemId);
        return;
    }
    
    fetch(`/admin/stockout/product-price/${productId}`)
        .then(r => r.json())
        .then(d => {
            document.querySelector(`.item-price[data-item-id="${itemId}"]`).value = d.unit_price || 0;
            calculateItemTotal(itemId);
        })
        .catch(err => console.error('Error fetching price:', err));
}

// Calculate Item Total
function calculateItemTotal(itemId) {
    const qty = parseFloat(document.querySelector(`.item-quantity[data-item-id="${itemId}"]`).value) || 0;
    const price = parseFloat(document.querySelector(`.item-price[data-item-id="${itemId}"]`).value) || 0;
    const total = qty * price;
    
    document.getElementById(`item-total-${itemId}`).textContent = `FRW ${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    calculateInvoiceTotal();
}

// Remove Invoice Item
function removeInvoiceItem(itemId) {
    document.getElementById(`item-row-${itemId}`).remove();
    
    // Renumber items
    let counter = 1;
    document.querySelectorAll('#invoiceItemsBody tr:not(.empty-state-row)').forEach(row => {
        const numberCell = row.querySelector('.item-number');
        if (numberCell) numberCell.textContent = counter++;
    });
    
    // Show empty state if no items
    if (document.querySelectorAll('#invoiceItemsBody tr').length === 0) {
        const tbody = document.getElementById('invoiceItemsBody');
        tbody.innerHTML = `
            <tr class="empty-state-row">
                <td colspan="6" class="text-center">
                    <i class="fas fa-box-open"></i>
                    <p>No products added yet. Click "Add Product" to start.</p>
                </td>
            </tr>
        `;
    }
    
    calculateInvoiceTotal();
}

// Calculate Invoice Total with 18% Tax
function calculateInvoiceTotal() {
    let subtotal = 0;
    document.querySelectorAll('.item-total').forEach(el => {
        const text = el.textContent.replace('FRW ', '').replace(/,/g, '');
        const amount = parseFloat(text) || 0;
        subtotal += amount;
    });
    
    const tax = subtotal * 0.18; // 18% tax
    const total = subtotal + tax;
    
    document.getElementById('displaySubtotal').textContent = `FRW ${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    document.getElementById('displayTax').textContent = `FRW ${tax.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    document.getElementById('displayTotal').textContent = `FRW ${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
}

// Process Payment
function processPayment() {
    const customerId = document.getElementById('customerSelect').value;
    if (!customerId) {
        showAlert('error', 'Please select a customer first');
        return;
    }
    
    const items = collectInvoiceItems();
    if (items.length === 0) {
        showAlert('error', 'Please add at least one product');
        return;
    }
    
    // Show payment modal
    const totalText = document.getElementById('displayTotal').textContent;
    document.getElementById('paymentAmount').textContent = totalText;
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentForm').reset();
    hideAllPaymentFields();
}

// Payment Method Selection
document.getElementById('paymentMethod').addEventListener('change', function() {
    hideAllPaymentFields();
    
    const method = this.value;
    if (method === 'mtn_momo' || method === 'airtel_money') {
        document.getElementById('mobileMoneyFields').classList.remove('hidden');
    } else if (method === 'bank_transfer') {
        document.getElementById('bankTransferFields').classList.remove('hidden');
    } else if (method === 'credit_card') {
        document.getElementById('cardFields').classList.remove('hidden');
    } else if (method === 'cash') {
        document.getElementById('cashFields').classList.remove('hidden');
    }
});

function hideAllPaymentFields() {
    document.querySelectorAll('.payment-fields').forEach(el => el.classList.add('hidden'));
}

// Calculate Cash Change
function calculateChange() {
    const totalText = document.getElementById('displayTotal').textContent.replace('FRW ', '').replace(/,/g, '');
    const total = parseFloat(totalText) || 0;
    const received = parseFloat(document.getElementById('cashReceived').value) || 0;
    const change = received - total;
    
    document.getElementById('cashChange').value = change >= 0 ? `FRW ${change.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'Insufficient amount';
}

// Payment Form Submit
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const method = document.getElementById('paymentMethod').value;
    
    // Validate based on payment method
    if (method === 'mtn_momo' || method === 'airtel_money') {
        const momoNumber = document.getElementById('momoNumber').value;
        if (!momoNumber) {
            showAlert('error', 'Please enter mobile money number');
            return;
        }
    } else if (method === 'bank_transfer') {
        const bankName = document.getElementById('bankName').value;
        const reference = document.getElementById('bankReference').value;
        if (!bankName || !reference) {
            showAlert('error', 'Please fill in all bank transfer details');
            return;
        }
    } else if (method === 'credit_card') {
        const cardNumber = document.getElementById('cardNumber').value;
        const expiry = document.getElementById('cardExpiry').value;
        const cvv = document.getElementById('cardCVV').value;
        if (!cardNumber || !expiry || !cvv) {
            showAlert('error', 'Please fill in all card details');
            return;
        }
    } else if (method === 'cash') {
        const received = parseFloat(document.getElementById('cashReceived').value) || 0;
        const totalText = document.getElementById('displayTotal').textContent.replace('FRW ', '').replace(/,/g, '');
        const total = parseFloat(totalText) || 0;
        if (received < total) {
            showAlert('error', 'Cash received is less than total amount');
            return;
        }
    }
    
    // Store payment method globally
    window.selectedPaymentMethod = method;
    
    // Payment successful
    paymentProcessed = true;
    showAlert('success', 'Payment processed successfully!');
    closePaymentModal();
    
    // Enable save & download button
    document.getElementById('btnSaveDownload').disabled = false;
    document.getElementById('btnProcessPayment').innerHTML = '<i class="fas fa-check-circle"></i> Payment Completed';
    document.getElementById('btnProcessPayment').style.background = '#10b981';
    document.getElementById('btnProcessPayment').disabled = true;
});

// Collect Invoice Items
function collectInvoiceItems() {
    const items = [];
    document.querySelectorAll('.item-product').forEach(select => {
        if (select.value) {
            const itemId = select.dataset.itemId;
            const productName = select.options[select.selectedIndex].text;
            const quantity = parseFloat(document.querySelector(`.item-quantity[data-item-id="${itemId}"]`).value) || 0;
            const price = parseFloat(document.querySelector(`.item-price[data-item-id="${itemId}"]`).value) || 0;
            
            if (quantity > 0 && price >= 0) {
                items.push({
                    productId: select.value,
                    productName: productName,
                    quantity: quantity,
                    price: price
                });
            }
        }
    });
    return items;
}

// Save and Download Invoice
function saveAndDownloadInvoice() {
    if (!paymentProcessed) {
        showAlert('error', 'Please process payment first');
        return;
    }
    
    // Check if payment method was stored
    if (!window.selectedPaymentMethod) {
        showAlert('error', 'Payment method not found. Please process payment again.');
        return;
    }
    
    const customerId = document.getElementById('customerSelect').value;
    const items = collectInvoiceItems();
    const notes = document.getElementById('invoiceNotes').value;
    
    // Prepare invoice data
    const invoiceData = {
        customer_id: customerId,
        items: items,
        notes: notes,
        payment_method: window.selectedPaymentMethod, // Use stored payment method
        subtotal: parseFloat(document.getElementById('displaySubtotal').textContent.replace('FRW ', '').replace(/,/g, '')),
        tax: parseFloat(document.getElementById('displayTax').textContent.replace('FRW ', '').replace(/,/g, '')),
        total: parseFloat(document.getElementById('displayTotal').textContent.replace('FRW ', '').replace(/,/g, ''))
    };
    
    // Save invoice and download PDF
    fetch("{{ route('admin.stockout.invoice.save') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(invoiceData)
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', 'Invoice saved successfully!');
            
            // Download PDF
            window.open(d.pdf_url, '_blank');
            
            // Redirect after delay
            setTimeout(() => {
                window.location.href = "{{ route('admin.stockout.index') }}";
            }, 2000);
        } else {
            showAlert('error', d.message || 'Error saving invoice');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to save invoice');
    });
}

// Close modals on outside click
window.addEventListener('click', function(e) {
    const customerModal = document.getElementById('customerModal');
    const paymentModal = document.getElementById('paymentModal');
    
    if (e.target === customerModal) closeCustomerModal();
    if (e.target === paymentModal) closePaymentModal();
});
</script>

