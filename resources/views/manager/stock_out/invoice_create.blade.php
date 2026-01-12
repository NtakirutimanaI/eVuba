@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    body {
        background: #f4f6f8;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        font-size: 13px;
    }

    .invoice-container {
        width: 70%;
        margin-left: 17%;
        margin-top: 40px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 20px;
        margin-bottom: 15px;
    }

    select,
    input {
        width: 100%;
        padding: 7px;
        margin-bottom: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
    }

    .add-btn {
        padding: 7px 12px;
        background: #007bff;
        border: none;
        color: white;
        border-radius: 6px;
        cursor: pointer;
    }

    .add-btn:hover {
        background: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 7px;
        font-size: 13px;
    }

    th {
        background: #007bff;
        color: white;
    }

    .remove-btn {
        background: #dc3545;
        color: white;
        padding: 5px 8px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .total-box {
        margin-top: 20px;
        padding: 15px;
        background: #f1f1f1;
        border-radius: 8px;
        font-size: 14px;
    }

    .submit-btn {
        background: #28a745;
        padding: 10px 20px;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 15px;
    }

    .payment-btn {
        background: #ffc107;
        padding: 10px 20px;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 40%;
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: black;
    }

    .payment-method-form {
        display: none;
        margin-top: 15px;
    }

    .payment-method-form input {
        width: 100%;
        padding: 7px;
        margin-bottom: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
</style>

<div class="invoice-container">
    <h2>Create Invoice</h2>

    <label><strong>Select Customer</strong></label>
    <select id="customerSelect">
        <option value="">-- Select Customer --</option>
        @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
    </select>

    <hr>

    <h3 style="font-size:16px;">Add Products</h3>
    <label><strong>Select Product</strong></label>
    <select id="productSelect">
        <option value="">-- Select Product --</option>
        @foreach($products as $p)
            <option value="{{ $p->id }}" data-price="{{ $p->unit_price ?? 0 }}" data-stock="{{ $p->current_stock ?? 0 }}">
                {{ $p->name }} (Stock: {{ $p->current_stock ?? 0 }})
            </option>
        @endforeach
    </select>

    <label><strong>Quantity</strong></label>
    <input type="number" id="qtyInput" min="1" value="1">

    <button class="add-btn" id="addProductBtn">Add Product</button>

    <table id="invoiceTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Revenue</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="total-box">
        <p><strong>Total Amount:</strong> <span id="totalAmount">0.00</span> FRW</p>
        <p><strong>Total Revenue:</strong> <span id="totalRevenue">0.00</span> FRW</p>
    </div>

    <button class="submit-btn" id="saveInvoiceBtn">Save Invoice</button>
    <button type="button" class="payment-btn" id="openPaymentModalBtn">Process Payment</button>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h3>Select Payment Method</h3>
        <select id="paymentMethodSelect">
            <option value="">-- Choose Method --</option>
            <option value="AirtelMoney">Airtel Money</option>
            <option value="MTNMobileMoney">MTN Mobile Money</option>
            <option value="VisaCard">Visa Card</option>
            <option value="BankAccount">Bank Account</option>
            <option value="Other">Other</option>
        </select>

        <div class="payment-method-form" id="AirtelMoneyForm">
            <label>Airtel Phone Number</label>
            <input type="text" id="airtelNumber" placeholder="Enter Airtel number">
        </div>

        <div class="payment-method-form" id="MTNMobileMoneyForm">
            <label>MTN Phone Number</label>
            <input type="text" id="mtnNumber" placeholder="Enter MTN number">
        </div>

        <div class="payment-method-form" id="VisaCardForm">
            <label>Card Number</label>
            <input type="text" id="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX">
            <label>Expiry Date</label>
            <input type="text" id="cardExpiry" placeholder="MM/YY">
            <label>CVV</label>
            <input type="text" id="cardCVV" placeholder="XXX">
        </div>

        <div class="payment-method-form" id="BankAccountForm">
            <label>Bank Account Number</label>
            <input type="text" id="bankAccount" placeholder="Enter account number">
            <label>Bank Name</label>
            <input type="text" id="bankName" placeholder="Enter bank name">
        </div>

        <div class="payment-method-form" id="OtherForm">
            <label>Other Payment Details</label>
            <input type="text" id="otherDetails" placeholder="Specify payment details">
        </div>

        <button class="submit-btn" id="confirmPaymentBtn" style="background:#17a2b8;">Confirm Payment</button>
    </div>
</div>

<script>
    let invoiceItems = [];
    let savedInvoiceId = null;

    function refreshTable() {
        let tbody = document.querySelector("#invoiceTable tbody");
        tbody.innerHTML = "";
        let totalAmount = 0;
        let totalRevenue = 0;

        invoiceItems.forEach((item, i) => {
            let qty = parseInt(item.qty) || 0;
            let price = parseFloat(item.price) || 0;
            let buy_price = parseFloat(item.buy_price) || 0;

            let subtotal = qty * price;
            let revenue = (price - buy_price) * qty;

            totalAmount += subtotal;
            totalRevenue += revenue;

            tbody.innerHTML += `
            <tr>
                <td>${i + 1}</td>
                <td>${item.name}</td>
                <td>${qty}</td>
                <td>${price.toFixed(2)}</td>
                <td>${subtotal.toFixed(2)}</td>
                <td>${revenue.toFixed(2)}</td>
                <td><button class="remove-btn" onclick="removeItem(${i})">X</button></td>
            </tr>
        `;
        });

        document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
        document.getElementById('totalRevenue').innerText = totalRevenue.toFixed(2);
    }

    function removeItem(index) {
        invoiceItems.splice(index, 1);
        refreshTable();
    }

    // Add product
    document.getElementById('addProductBtn').onclick = () => {
        let productSelect = document.getElementById('productSelect');
        let productId = productSelect.value;
        if (!productId) return alert("Select a product");

        let option = productSelect.options[productSelect.selectedIndex];
        let productName = option.text;
        let price = parseFloat(option.dataset.price) || 0;
        let stock = parseInt(option.dataset.stock) || 0;
        let qty = parseInt(document.getElementById("qtyInput").value) || 0;

        if (price <= 0) return alert("Product price invalid");
        if (qty <= 0) return alert("Enter a valid quantity");
        if (qty > stock) return alert("Quantity exceeds available stock!");

        let buy_price = price * 0.8;

        invoiceItems.push({
            product_id: productId,
            name: productName,
            qty: qty,
            price: price,
            buy_price: buy_price
        });

        refreshTable();
    };

    // Save invoice
    document.getElementById('saveInvoiceBtn').onclick = () => {
        let customer = document.getElementById("customerSelect").value;
        if (!customer) return alert("Select customer");
        if (invoiceItems.length === 0) return alert("Add at least one product");

        fetch("{{ route('manager.invoice.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                customer_id: customer,
                items: invoiceItems
            })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    alert("Invoice saved!");
                    savedInvoiceId = res.invoice_id;
                } else {
                    alert(res.message || "Error saving invoice");
                }
            })
            .catch(e => alert("Error saving invoice: " + e));
    };

    // Open payment modal
    document.getElementById('openPaymentModalBtn').onclick = () => {
        if (!savedInvoiceId) return alert("Save the invoice first!");
        document.getElementById('paymentModal').style.display = 'block';
    };

    // Close modal
    document.getElementById('closeModal').onclick = () => {
        document.getElementById('paymentModal').style.display = 'none';
    };

    // Show payment form
    document.getElementById('paymentMethodSelect').onchange = (e) => {
        let methods = ['AirtelMoney', 'MTNMobileMoney', 'VisaCard', 'BankAccount', 'Other'];
        methods.forEach(m => document.getElementById(m + 'Form').style.display = 'none');
        if (e.target.value) document.getElementById(e.target.value + 'Form').style.display = 'block';
    };

    // Confirm payment
    document.getElementById('confirmPaymentBtn').onclick = () => {
        if (!savedInvoiceId) return alert("Save invoice first!");

        let method = document.getElementById('paymentMethodSelect').value;
        if (!method) return alert("Select payment method!");

        let paymentData = {};
        switch (method) {
            case 'AirtelMoney': paymentData.phone = document.getElementById('airtelNumber').value; break;
            case 'MTNMobileMoney': paymentData.phone = document.getElementById('mtnNumber').value; break;
            case 'VisaCard':
                paymentData.card = document.getElementById('cardNumber').value;
                paymentData.expiry = document.getElementById('cardExpiry').value;
                paymentData.cvv = document.getElementById('cardCVV').value;
                break;
            case 'BankAccount':
                paymentData.account = document.getElementById('bankAccount').value;
                paymentData.bank = document.getElementById('bankName').value;
                break;
            case 'Other': paymentData.details = document.getElementById('otherDetails').value; break;
        }

        fetch(`/manager/invoice/payment/${savedInvoiceId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                method: method,
                data: paymentData
            })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    alert("Payment processed successfully!");
                    document.getElementById('paymentModal').style.display = 'none';
                    window.location.href = `/manager/invoice/${savedInvoiceId}/print`;
                } else {
                    alert(res.message || "Payment failed. Try again.");
                }
            })
            .catch(e => alert("Error processing payment: " + e));
    };
</script>