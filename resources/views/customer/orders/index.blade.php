@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-store" style="color: var(--primary);"></i> Commercial Hub</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Browse our professional catalog and manage your
                procurement pipeline.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel"
                style="padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                <div class="stat-item">
                    <span style="font-size: 0.75rem; color: var(--secondary);">Active Acquisitions</span>
                    <strong
                        style="display: block; font-size: 1.1rem; color: var(--primary);">{{ $orders->whereIn('status', ['pending', 'processing'])->count() }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div style="margin-top: 2rem; display: flex; gap: 15px; align-items: center; margin-bottom: 25px;">
        <div class="mega-search" style="max-width: 400px; flex: 1;">
            <i class="fas fa-search"></i>
            <input type="text" id="marketplaceSearch" placeholder="Search catalog or orders..." class="search-input">
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="filter-pill active" onclick="toggleView('marketplace')">Marketplace</button>
            <button class="filter-pill" onclick="toggleView('archive')">Order Archive</button>
        </div>
    </div>

    {{-- Marketplace Grid --}}
    <div id="marketplaceView" class="view-section">
        <div class="procurement-grid">
            @forelse($products as $product)
                <div class="product-card"
                    data-search="{{ strtolower($product->name . ' ' . ($product->category->name ?? '')) }}">
                    <div class="product-visual">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div class="fallback-visual"><i class="fas fa-box-open"></i></div>
                        @endif
                        <div class="category-badge">{{ $product->category->name ?? 'General' }}</div>
                        @if($product->unit_price > 0)
                            <div class="price-tag">{{ number_format($product->unit_price) }} FRW</div>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description, 60) }}</p>

                        <div style="display: flex; gap: 8px;">
                            <button class="procure-btn secondary" onclick="viewProductDetails({{ json_encode($product) }})"
                                style="flex: 1; background: var(--light); color: var(--secondary); border-color: var(--glass-border);">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="procure-btn" onclick="addToCart({{ json_encode($product) }}, this)"
                                style="flex: 2;">
                                Place Order <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-panel" style="grid-column: 1/-1; text-align: center; padding: 60px;">
                    <i class="fas fa-boxes"
                        style="font-size: 3rem; color: var(--primary); opacity: 0.2; margin-bottom: 20px;"></i>
                    <p style="color: var(--secondary);">The marketplace catalog is currently initializing.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Order Archive --}}
    <div id="archiveView" class="view-section" style="display: none;">
        <div class="glass-panel" style="padding: 0; overflow: hidden; border-radius: 20px;">
            <table class="pro-table">
                <thead>
                    <tr>
                        <th style="padding-left: 25px;">Acquisition ID</th>
                        <th>Product Manifest</th>
                        <th>Volume</th>
                        <th>Deployment Price</th>
                        <th>Final Status</th>
                        <th style="text-align: right; padding-right: 25px;">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="order-row"
                            data-search="{{ strtolower(($order->product->name ?? $order->product_name) . ' ' . $order->status) }}">
                            <td style="padding-left: 25px;">
                                <span
                                    style="font-family: monospace; font-size: 0.8rem; color: var(--secondary);">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">
                                    {{ $order->product->name ?? $order->product_name }}
                                </div>
                                <div style="font-size: 0.7rem; color: var(--secondary);">
                                    {{ $order->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td><span style="font-weight: 800;">x{{ $order->quantity }}</span></td>
                            <td><span
                                    style="color: #10b981; font-weight: 700;">{{ number_format($order->price * $order->quantity) }}
                                    FRW</span></td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ $order->status == 'approved' ? 'Paid' : ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="text-align: right; padding-right: 25px;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    @if(!in_array($order->payment_status, ['paid', 'approved']))
                                        <form action="{{ route('payment.initiate') }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <button type="submit" class="icon-btn" title="Pay Now"
                                                style="color: #10b981; border: none; background: transparent; cursor: pointer;">
                                                <i class="fas fa-credit-card"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('customer.orders.invoice', $order->id) }}" class="icon-btn"
                                        title="Download Invoice"
                                        style="color: var(--primary); display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                    <button class="icon-btn" onclick="deleteOrder({{ $order->id }})" title="Delete Order"
                                        style="color: #ef4444;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 60px; text-align: center; color: var(--secondary);">
                                <i class="fas fa-history"
                                    style="font-size: 2.5rem; opacity: 0.1; margin-bottom: 15px; display: block;"></i>
                                No acquisitions detected in your archive.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Order Modal --}}
<div id="orderModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="fas fa-shopping-basket"></i> Acquisition Manifest</h3>
            <button class="close-modal" onclick="closeOrderModal()">&times;</button>
        </div>
        <form id="acquisitionForm" action="{{ route('customer.orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">
            <div class="modal-body" style="padding: 30px;">
                <div
                    style="display: flex; gap: 20px; align-items: start; margin-bottom: 25px; background: var(--light); padding: 15px; border-radius: 15px;">
                    <img id="modalProductImage" src=""
                        style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
                    <div>
                        <h4 id="modalProductName"
                            style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--dark);">Product Name
                        </h4>
                        <div id="modalProductPrice" style="color: #10b981; font-weight: 700; margin-top: 5px;">0 FRW
                        </div>

                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--secondary); margin-bottom: 10px;">Acquisition
                        Volume (Quantity)</label>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <input type="number" name="quantity" id="modalQuantity" class="pro-input" value="1" min="1"
                            required style="width: 100px; text-align: center; font-size: 1.1rem; font-weight: 800;">
                        <div style="flex: 1; text-align: right;">
                            <div
                                style="font-size: 0.7rem; color: var(--secondary); text-transform: uppercase; letter-spacing: 1px;">
                                Estimated Investment</div>
                            <div id="estimatedTotal"
                                style="font-size: 1.2rem; font-weight: 900; color: var(--primary);">0 FRW</div>
                        </div>
                    </div>
                </div>

                {{-- Auto-filled context for acquisition --}}
                <div
                    style="font-size: 0.75rem; color: var(--secondary); line-height: 1.5; background: rgba(99, 102, 241, 0.05); padding: 12px; border-radius: 10px; border-left: 3px solid var(--primary);">
                    <i class="fas fa-info-circle"></i> This procurement will be registered to your primary account
                    identifier and processed by our logistics department.
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px 30px; background: var(--light);">
                <button type="button" class="btn-cancel" onclick="closeOrderModal()"
                    style="border: none; background: transparent;">Cancel</button>
                <button type="submit" class="action-btn btn-primary"
                    style="width: auto; padding: 0 30px; border-radius: 14px; height: 50px; font-weight: 800;">
                    Confirm Acquisition <i class="fas fa-check"></i>
                </button>
            </div>
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-container {
        z-index: 3000 !important;
    }

    /* Compact Archive Table (User Request: "Make cards small") */
    #archiveView .pro-table {
        border-spacing: 0 5px;
        /* Reduce gap between rows */
    }

    #archiveView .pro-table th {
        padding: 10px 15px;
        font-size: 0.75rem;
    }

    #archiveView .pro-table td {
        padding: 8px 15px;
        /* Compact padding */
        font-size: 0.85rem;
    }

    #archiveView .pro-table .status-badge {
        padding: 2px 8px;
        font-size: 0.65rem;
    }

    #archiveView .icon-btn {
        width: 28px;
        height: 28px;
        font-size: 0.85rem;
    }
</style>
<script>
    let currentProduct = null;
    let cart = JSON.parse(localStorage.getItem('evuba_cart')) || [];

    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');
        if (section === 'archive') toggleView('archive');
        updateCartBadge();
    });

    function toggleView(view) {
        document.getElementById('marketplaceView').style.display = view === 'marketplace' ? 'block' : 'none';
        document.getElementById('archiveView').style.display = view === 'archive' ? 'block' : 'none';

        document.querySelectorAll('.filter-pill').forEach(btn => {
            btn.classList.toggle('active', btn.innerText.toLowerCase().includes(view));
        });
    }

    // --- Cart Functions ---
    function addToCart(product, btnElement) {
        const existing = cart.find(p => p.id === product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        saveCart();

        const btn = btnElement || (typeof event !== 'undefined' ? event.currentTarget : null);
        if (btn) {
            const orgHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Added';
            btn.style.background = '#10b981';
            btn.style.color = 'white';
            setTimeout(() => {
                btn.innerHTML = orgHtml;
                btn.style.background = '';
                btn.style.color = '';
            }, 1000);
        }

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Added to cart',
            showConfirmButton: false,
            timer: 1500,
            background: 'var(--white)',
            color: 'var(--text-main)'
        });
    }

    function saveCart() {
        localStorage.setItem('evuba_cart', JSON.stringify(cart));
        updateCartBadge();
    }

    function updateCartBadge() {
        const badge = document.getElementById('cartBadge');
        const count = cart.reduce((acc, item) => acc + item.quantity, 0);
        if (badge) {
            badge.innerText = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    // Connect Cart Icon in Header to View Cart Logic
    const cartIcon = document.querySelector('a[title="My Shopping Cart"]');
    if (cartIcon) {
        cartIcon.addEventListener('click', (e) => {
            e.preventDefault();
            viewCart();
        });
    }

    function viewCart() {
        // Explicitly check theme to force correct colors
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#1e293b' : '#ffffff';
        const textColor = isDark ? '#e2e8f0' : '#334155';
        const titleColor = isDark ? '#f8fafc' : '#0f172a';

        if (cart.length === 0) {
            Swal.fire({
                title: 'Your Cart is Empty',
                text: 'Browse the marketplace to add items.',
                icon: 'info',
                background: bgColor,
                color: textColor
            });
            return;
        }

        let html = `
            <div style="text-align: left; max-height: 300px; overflow-y: auto;">
                <table class="pro-table" style="width: 100%;">
                    <thead><tr><th style="color: var(--secondary);">Product</th><th style="color: var(--secondary);">Qty</th><th style="color: var(--secondary);">Price</th><th style="color: var(--secondary);">Action</th></tr></thead>
                    <tbody>
        `;
        let total = 0;

        cart.forEach((item, index) => {
            let itemTotal = item.unit_price * item.quantity;
            total += itemTotal;
            html += `
                <tr>
                    <td style="color: ${textColor}; font-weight: 600;">${item.name}</td>
                    <td>
                        <input type="number" min="1" value="${item.quantity}" 
                               style="width: 60px; padding: 6px; border-radius: 8px; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--text-main); font-weight: 700; text-align: center;"
                               onchange="updateCartItem(${index}, this.value)">
                    </td>
                    <td style="color: var(--success); font-weight: 700;">${new Intl.NumberFormat().format(itemTotal)} FRW</td>
                    <td><button onclick="removeCartItem(${index})" style="color: #ef4444; border:none; background:none; cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
        });

        html += `</tbody></table></div>
        <div style="text-align: right; margin-top: 20px; font-weight: 900; font-size: 1.3rem; color: var(--primary); padding-top: 15px; border-top: 1px solid var(--glass-border);">
            Total: <span style="color: ${textColor};">${new Intl.NumberFormat().format(total)} FRW</span>
        </div>`;

        Swal.fire({
            title: `<span style="color: ${titleColor};">Your Cart</span>`,
            html: html,
            showCancelButton: true,
            confirmButtonText: 'Process Payment <i class="fas fa-credit-card"></i>',
            cancelButtonText: 'Continue Shopping',
            width: 650,
            background: bgColor,
            color: textColor,
            didOpen: () => {
                Swal.getPopup().style.borderRadius = '20px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Directly process checkout - no payment method selection
                processCheckout();
            }
        });
    }

    window.updateCartItem = function (index, qty) {
        if (qty < 1) return;
        cart[index].quantity = parseInt(qty);
        saveCart();
        // Re-open cart to refresh totals is tricky with Swal, 
        // simplified: close logic handled by swal or re-trigger viewCart()
        // Here we just save, user sees stale data until re-open. 
        // Better: trigger viewCart() again immediately.
        Swal.close();
        setTimeout(viewCart, 100);
    };

    window.removeCartItem = function (index) {
        cart.splice(index, 1);
        saveCart();
        Swal.close();
        setTimeout(viewCart, 100);
    };

    async function processCheckout() {
        // Explicitly check theme for consistent modal styling
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#1e293b' : '#ffffff';
        const textColor = isDark ? '#e2e8f0' : '#334155';

        Swal.fire({
            title: 'Processing Order',
            text: 'Please wait...',
            allowOutsideClick: false,
            background: bgColor,
            color: textColor,
            didOpen: () => Swal.showLoading()
        });

        // Submit each item individually (since backend expects single orders)
        let successCount = 0;
        let orderIds = [];

        for (const item of cart) {
            try {
                const formData = new FormData();
                formData.append('product_id', item.id);
                formData.append('quantity', item.quantity);

                const response = await fetch("{{ route('customer.orders.store') }}", {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.order_id) {
                    successCount++;
                    orderIds.push(data.order_id);
                }
            } catch (e) {
                console.error('Order failed for', item.name);
            }
        }

        if (successCount === cart.length && orderIds.length > 0) {
            // Clear cart
            cart = [];
            saveCart();

            // For multiple orders, process payment for the first one
            // In a real scenario, you might want to create a combined payment
            const orderId = orderIds[0];

            // Create a form and submit to payment initiation route
            const paymentForm = document.createElement('form');
            paymentForm.method = 'POST';
            paymentForm.action = "{{ route('payment.initiate') }}";

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const orderIdInput = document.createElement('input');
            orderIdInput.type = 'hidden';
            orderIdInput.name = 'order_id';
            orderIdInput.value = orderId;

            paymentForm.appendChild(csrfInput);
            paymentForm.appendChild(orderIdInput);
            document.body.appendChild(paymentForm);

            Swal.fire({
                title: 'Order Created!',
                text: 'Redirecting to payment page...',
                icon: 'success',
                background: bgColor,
                color: textColor,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                paymentForm.submit();
            });
        } else {
            Swal.fire({
                title: 'Warning',
                text: 'Some items could not be processed.',
                icon: 'warning',
                background: bgColor,
                color: textColor
            }).then(() => location.reload());
        }
    }

    // --- Search Logic ---
    document.getElementById('marketplaceSearch').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = card.dataset.search.includes(filter) ? '' : 'none';
        });
        document.querySelectorAll('.order-row').forEach(row => {
            row.style.display = row.dataset.search.includes(filter) ? '' : 'none';
        });
    });

    function viewProductDetails(product) {
        // Use CSS variables for placeholder background to support dark mode
        const imageHtml = product.image
            ? `<img src="/storage/${product.image}" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">`
            : `<div style="width: 100%; height: 200px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; border-radius: 12px; margin-bottom: 20px;"><i class="fas fa-box-open" style="font-size: 3rem; color: var(--secondary);"></i></div>`;

        Swal.fire({
            title: `<span style="color: var(--dark);">${product.name}</span>`,
            html: `
                <div style="text-align: left; color: var(--text-main);">
                    ${imageHtml}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span class="category-badge" style="position: static; transform: none;">${product.category ? product.category.name : 'General'}</span>
                        <span style="font-weight: 800; color: var(--success); font-size: 1.2rem; display: ${product.unit_price > 0 ? 'inline' : 'none'};">${new Intl.NumberFormat().format(product.unit_price)} FRW</span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">
                        ${product.description || 'No description available for this item.'}
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--glass-border); padding-top: 15px;">

                        <button id="modalAddToCart" class="procure-btn" style="width: auto; padding: 10px 20px;">
                            Add to Cart <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: 550,
            background: 'var(--white)', // Ensure modal bg uses theme variable
            color: 'var(--text-main)',   // Ensure default text uses theme variable
            didOpen: () => {
                // Manually apply border radius to match theme without class conflicts
                Swal.getPopup().style.borderRadius = '20px';

                const btn = document.getElementById('modalAddToCart');
                if (btn) {
                    btn.addEventListener('click', (e) => {
                        addToCart(product, e.currentTarget);
                    });
                }
            }
        });
    }

    function deleteOrder(id) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#1e293b' : '#ffffff';
        const textColor = isDark ? '#e2e8f0' : '#334155';

        Swal.fire({
            title: 'Delete Acquisition?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            background: bgColor,
            color: textColor
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('customer/orders') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Your order has been deleted.',
                                icon: 'success',
                                background: bgColor,
                                color: textColor
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                background: bgColor,
                                color: textColor
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            background: bgColor,
                            color: textColor
                        });
                    });
            }
        })
    }

    function viewReceipt(id) {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#1e293b' : '#ffffff';
        const textColor = isDark ? '#e2e8f0' : '#334155';

        Swal.fire({
            title: 'Technical Manifest',
            text: 'System is generating acquisition receipt...',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false,
            background: bgColor,
            color: textColor
        });
    }

    window.onclick = (e) => {
        // Modal handlers if any remain standard
    }

    // Flash Messages for Callbacks (like Successful Payment)
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#ffffff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#e2e8f0' : '#334155'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#ffffff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#e2e8f0' : '#334155'
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            title: 'Notice',
            text: "{{ session('warning') }}",
            icon: 'warning',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#ffffff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#e2e8f0' : '#334155'
        });
    @endif
</script>