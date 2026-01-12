@include('layouts.header')
@include('layouts.sidebar')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background: #f4f6f8;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        font-size: 12px;
    }

    .inventory-container {
        width: 81.5%;
        margin-left: 17.5%;
        margin-top: 70px;
    }

    .report-box,
    .left-fixed {
        background: white;
        padding: 15px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .report-title {
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .generate-btn {
        padding: 8px;
        font-size: 12px;
        width: 100%;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .add-btn {
        background: #007bff;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        padding: 6px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background: #007bff;
        color: white;
    }

    .delete-btn {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 3px 6px;
    }

    /* MODALS */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .55);
        z-index: 3000;
    }

    .modal-content {
        background: white;
        width: 420px;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
    }

    .close-btn {
        float: right;
        cursor: pointer;
        font-size: 18px;
    }

    .save-btn {
        background: #28a745;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: fit-content;
        display: block;
        margin: 20px auto 10px auto;
        font-weight: 600;
        font-size: 13px;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .pay-btn {
        padding: 12px;
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        font-weight: 600;
        font-size: 11px;
    }

    .pay-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .pay-btn.active {
        border-color: #007bff;
        background: #f0f7ff;
        color: #007bff;
    }

    .pay-btn.momo {
        background: #ffcc00;
        color: #000;
    }

    .pay-btn.airtel {
        background: #e30613;
        color: #fff;
    }

    .pay-btn.bank {
        background: #6f42c1;
        color: #fff;
    }

    .pay-btn.card {
        background: #28a745;
        color: #fff;
    }

    .pay-btn.cash {
        background: #6c757d;
        color: #fff;
    }



    <div class="inventory-container"><div style="display:flex; gap:15px;">< !-- PRODUCTS --><div style="flex:2"><div class="report-box"><div class="report-title" style="font-size: 14px; margin-bottom: 12px;">Available Products</div><div style="background: #f8f9fa; padding: 8px; border-radius: 6px; border: 1px solid #e9ecef; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;"><button class="add-btn" onclick="addSelected()" style="padding: 5px 12px; font-size: 11px; font-weight: 600; background-color: #007bff; border: none; border-radius: 4px; color: white; cursor: pointer; white-space: nowrap;">➕ Add Selected to Invoice </button><div style="display: flex; align-items: center; gap: 6px; flex: 1;"><label style="font-size: 11px; font-weight: 700; color: #495057; white-space: nowrap;">Customer Selection:</label><select id="customerSelect" onchange="updateCustomerInfo(this)" style="flex: 1; padding: 4px 8px; border-radius: 4px; border: 1px solid #ced4da; font-size: 11px; color: #495057; background-color: #fff;"><option value="">-- Choose Customer --</option>
    @foreach($customers as $c)
        <option value="{{ $c->id }}"
        data-name="{{ $c->name }}"
        data-email="{{ $c->email }}"
        data-phone="{{ $c->phone }}"
        data-address="{{ $c->address }}">
        {{ $c->name }}
    </option>@endforeach </select></div></div><table><thead><tr><th><input type="checkbox" id="checkAll"></th><th>Code</th><th>Name</th><th>Price</th></tr></thead><tbody>
    @foreach($products as $p)
        <tr><td><input type="checkbox" class="pcheck"
        data-id="{{ $p->id }}"
        data-name="{{ $p->name }}"
    data-price="{{ $p->unit_price }}"></td><td>{{ $p->product_code }}</td><td>{{ $p->name }}</td><td>{{ number_format($p->unit_price) }} FRW</td></tr>@endforeach </tbody></table></div></div>< !-- INVOICE --><div class="left-fixed" style="flex:1"><div class="report-title">Invoice Preview</div><table><thead><tr><th>Product</th><th>Qty</th><th>Total</th><th></th></tr></thead><tbody id="invoiceBody"><tr><td colspan="4" align="center">No items</td></tr></tbody></table><hr><p>Subtotal: <strong id="sub">0</strong>FRW</p><p>Tax (18%): <strong id="tax">0</strong>FRW</p><p><strong>Grand Total: <span id="grand">0</span>FRW</strong></p><button class="generate-btn" style="background:#007bff" onclick="openPayment()">Process Payment & Create Invoice</button></div></div></div><div class="modal" id="payModal"><div class="modal-content" style="width: 480px;"><span class="close-btn" onclick="closePay()">✕</span><h3 style="margin-top: 0; color: #1a2a4a;">Payment Confirmation</h3><p style="background: #f8faff; padding: 10px; border-radius: 6px; border-left: 4px solid #007bff;">Amount Due: <strong id="payAmount" style="font-size: 18px; color: #e63946;"></strong>FRW</p><div class="payment-grid"><div class="pay-btn momo" onclick="selectMethod('Phone / Reference','momo', this)">MTN MoMo</div><div class="pay-btn airtel" onclick="selectMethod('Phone / Reference','airtel', this)">Airtel Money</div><div class="pay-btn bank" onclick="selectMethod('Acc / Tx ID','bank', this)">Bank Transfer</div><div class="pay-btn card" onclick="selectMethod('Card / Tx ID','card', this)">Card Payment</div><div class="pay-btn cash active" onclick="selectMethod('Reference (Optional)','cash', this)">Cash</div></div><input id="payInput" placeholder="Reference (Optional)" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 15px;"><button class="generate-btn" style="width: 100%; padding: 12px; font-weight: 700; background: #28a745;" onclick="confirmPayment()">Save Invoice & Get PDF</button><div class="success-msg" id="paySuccess" style="margin-top: 15px; padding: 12px; text-align: center; border-radius: 6px; background: #d4edda; color: #155724; display: none;">✅ Payment processed ! Redirecting to Invoice... </div></div></div>< !-- INVOICE MODAL --><div class="modal" id="invoiceModal"><div class="modal-content" style="width:900px;"><span class="close-btn" onclick="closeInvoice()">✕</span>
    @include('admin.invoices.invoice-pdf')

    <button class="save-btn" onclick="savePDF()">Save Invoice as PDF</button></div></div><script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script><script>const items= {}

    ;
    let payMethod='Cash';
    let selectedCustomerId=null;
    let selectedCustomerName='';

    const {
        jsPDF
    }

    =window.jspdf;

    // Update customer info displayed on invoice
    function updateCustomerInfo(select) {
        const opt=select.options[select.selectedIndex];

        if( !opt.value) {
            selectedCustomerId=null;
            selectedCustomerName='';
            document.getElementById('billName').innerText='Choose Customer';
            document.getElementById('billAddress').innerText='Address';
            document.getElementById('billMail').innerText='Mail';
            document.getElementById('billPhone').innerText='Phone';
            return;
        }

        selectedCustomerId=opt.value;
        selectedCustomerName=opt.dataset.name;
        document.getElementById('billName').innerText=opt.dataset.name;
        document.getElementById('billAddress').innerText=opt.dataset.address || 'N/A';
        document.getElementById('billMail').innerText=opt.dataset.email || 'N/A';
        document.getElementById('billPhone').innerText=opt.dataset.phone || 'N/A';
    }

    document.getElementById('checkAll').onchange=e=> {
        document.querySelectorAll('.pcheck').forEach(c=> c.checked=e.target.checked);
    }

    ;

    function addSelected() {
        document.querySelectorAll('.pcheck:checked').forEach(c=> {
                if ( !items[c.dataset.id]) {
                    items[c.dataset.id]= {
                        name: c.dataset.name, price: +c.dataset.price, qty: 1
                    }

                    ;
                }
            });
        render();
    }

    function render() {
        let b=document.getElementById('invoiceBody');
        b.innerHTML='';
        let subtotal=0;

        Object.entries(items).forEach(([id, i], index)=> {
                let t=i.qty * i.price;
                subtotal +=t;

                b.innerHTML +=`<tr> <td>$ {
                    i.name
                }

                </td> <td><input type="number" value="${i.qty}" min="1" style="width:50px" onchange="items[${id}].qty=parseInt(this.value);render()" ></td> <td>$ {
                    t.toLocaleString()
                }

                </td> <td><button class="delete-btn" onclick="delete items[${id}];render()" >X</button></td> </tr>`;
            });
        let tax=Math.round(subtotal * 0.18);
        let grand=subtotal+tax;

        document.getElementById('sub').innerText=subtotal.toLocaleString();
        document.getElementById('tax').innerText=tax.toLocaleString();
        document.getElementById('grand').innerText=grand.toLocaleString();
    }

    function openPayment() {
        if(Object.keys(items).length===0) {
            alert("Add products first!");
            return;
        }

        if( !selectedCustomerId) {
            alert("Select a customer first!");
            return;
        }

        const total=document.getElementById('grand').innerText;
        document.getElementById('payAmount').innerText=total;
        document.getElementById('payInput').value='';
        document.getElementById('payModal').style.display='block';
    }

    function closePay() {
        document.getElementById('payModal').style.display='none';
    }

    function selectMethod(ph, methodType, el) {
        document.getElementById('payInput').placeholder=ph;
        payMethod=methodType;
        document.querySelectorAll('.pay-btn').forEach(b=> b.classList.remove('active'));
        el.classList.add('active');
    }

    // Global variable to store saved invoice ID
    let currentInvoiceId=null;

    async function confirmPayment() {
        let v=document.getElementById('payInput').value.trim();

        if( !v) {
            alert("Enter the payment details!");
            return;
        }

        const subtotal=parseFloat(document.getElementById('sub').innerText.replace(/, /g, ''));
        const taxAmount=parseFloat(document.getElementById('tax').innerText.replace(/, /g, ''));
        const grandTotal=parseFloat(document.getElementById('grand').innerText.replace(/, /g, ''));

        const data= {
            customer_id: selectedCustomerId,
                customer_name: selectedCustomerName,
                invoice_date: new Date().toISOString().split('T')[0],
                subtotal: subtotal,
                tax_amount: taxAmount,
                grand_total: grandTotal,
                payment_method: payMethod,
                status: 'paid',
                items: items,
                _token: '{{ csrf_token() }}'
        }

        ;

        const btn=document.querySelector('#payModal .generate-btn');
        const oldBtnText=btn.innerText;
        btn.innerText='🔄 Processing Payment...';
        btn.disabled=true;

        // Simulate Payment API Call with delay
        setTimeout(async ()=> {
                try {
                    const response=await fetch("{{ route('admin.invoices.store') }}", {

                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', 'Accept': 'application/json'
                        }

                        ,
                        body: JSON.stringify(data)
                    });
                const result=await response.json();

                if(result.success) {
                    currentInvoiceId=result.invoice_id;
                    document.getElementById('paySuccess').style.display='block';
                    btn.innerText='✅ Success! Loading PDF...';

                    setTimeout(()=> {
                            // Redirect to PDF route
                            window.location.href="{{ url('admin/invoices') }}/" + currentInvoiceId + "/pdf";
                        }

                        , 1500);
                }

                else {
                    alert("Error: " + result.message);
                    btn.innerText=oldBtnText;
                    btn.disabled=false;
                }
            }

            catch (e) {
                alert("Request failed: " + e.message);
                btn.innerText=oldBtnText;
                btn.disabled=false;
            }
        }

        , 2000);
    }

    function openInvoice() {
        if(Object.keys(items).length===0) {
            alert("Add products first!");
            return;
        }

        if( !selectedCustomerId) {
            alert("Select a customer first!");
            return;
        }

        let body=document.getElementById('invoiceItems');
        body.innerHTML='';
        let subtotal=0;
        let index=1;

        Object.values(items).forEach(i=> {
                let t=i.qty * i.price;
                subtotal +=t;

                body.innerHTML +=`<tr> <td>$ {
                    index
                }

                </td> <td>$ {
                    i.name
                }

                </td> <td style="text-align:center;" >$ {
                    i.qty
                }

                </td> <td style="text-align:right;" >$ {
                    i.price.toLocaleString()
                }

                FRW</td> <td style="text-align:right;" >$ {
                    t.toLocaleString()
                }

                FRW</td> </tr>`;
                index++;
            });
        let tax=Math.round(subtotal * 0.18);
        let grand=subtotal+tax;

        document.getElementById('iSub').innerText=subtotal.toLocaleString()+" FRW";
        document.getElementById('iTax').innerText=tax.toLocaleString()+" FRW";
        document.getElementById('iGrand').innerText=grand.toLocaleString()+" FRW";
        document.getElementById('invoiceModal').style.display='block';
    }

    function closeInvoice() {
        document.getElementById('invoiceModal').style.display='none';
    }

    async function savePDF() {

        // If not saved yet, save it first
        if( !currentInvoiceId) {
            const subtotal=parseFloat(document.getElementById('sub').innerText.replace(/, /g, ''));
            const taxAmount=parseFloat(document.getElementById('tax').innerText.replace(/, /g, ''));
            const grandTotal=parseFloat(document.getElementById('grand').innerText.replace(/, /g, ''));

            const data= {
                customer_id: selectedCustomerId,
                    customer_name: selectedCustomerName,
                    invoice_date: new Date().toISOString().split('T')[0],
                    subtotal: subtotal,
                    tax_amount: taxAmount,
                    grand_total: grandTotal,
                    payment_method: 'Cash',
                    status: 'pending',
                    items: items,
                    _token: '{{ csrf_token() }}'
            }

            ;

            try {
                const response=await fetch("{{ route('admin.invoices.store') }}", {

                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', 'Accept': 'application/json'
                    }

                    ,
                    body: JSON.stringify(data)
                });
            const result=await response.json();

            if(result.success) {
                currentInvoiceId=result.invoice_id;
            }

            else {
                alert("Error saving invoice: " + result.message);
                return;
            }
        }

        catch (e) {
            alert("Save failed: " + e.message);
            return;
        }
    }

    const doc=new jsPDF({
        unit: 'pt',
        format: 'a4',
        orientation: 'p'
    });

    const content=document.getElementById('invoiceContent');

    // Scale and padding adjustments to prevent clipping
    doc.html(content, {
        callback: function(pdf) {
            pdf.save("invoice_" + currentInvoiceId + ".pdf");

            setTimeout(()=> {
                    location.reload();
                }

                , 1500);
        }

        ,
        x: 0,
        y: 0,
        width: 595, // A4 width in pts
        windowWidth: 550, // Matching invoice-container width

        html2canvas: {
            scale: 0.85, // Scale down slightly to ensure it fits with margins
            useCORS: true,
            logging: false,
            letterRendering: true
        }
    });
    }

    </script>