@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-store" style="color: var(--primary);"></i> Commercial Hub</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Browse our professional catalog and manage your procurement pipeline.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                <div class="stat-item">
                    <span style="font-size: 0.75rem; color: var(--secondary);">Active Acquisitions</span>
                    <strong style="display: block; font-size: 1.1rem; color: var(--primary);">{{ $orders->whereIn('status', ['pending', 'processing'])->count() }}</strong>
                </div>
                <div style="width: 1px; height: 30px; background: var(--glass-border);"></div>
                <div class="stat-item">
                    <span style="font-size: 0.75rem; color: var(--secondary);">Total Investment</span>
                    <strong style="display: block; font-size: 1.1rem; color: #10b981;">{{ number_format($orders->where('status', 'completed')->sum(fn($o) => $o->price * $o->quantity)) }} RWF</strong>
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
                <div class="product-card" data-search="{{ strtolower($product->name . ' ' . ($product->category->name ?? '')) }}">
                    <div class="product-visual">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div class="fallback-visual"><i class="fas fa-box-open"></i></div>
                        @endif
                        <div class="category-badge">{{ $product->category->name ?? 'General' }}</div>
                        <div class="price-tag">{{ number_format($product->selling_price) }} RWF</div>
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description, 60) }}</p>
                        <div class="product-meta">
                            <span class="{{ ($product->stock_quantity ?? 0) > 0 ? 'instock' : 'outstock' }}">
                                <i class="fas fa-warehouse"></i> {{ $product->stock_quantity ?? 0 }} in Stock
                            </span>
                        </div>
                        <button class="procure-btn" onclick="openOrderModal({{ json_encode($product) }})">
                            Initialize Procurement <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="glass-panel" style="grid-column: 1/-1; text-align: center; padding: 60px;">
                    <i class="fas fa-boxes" style="font-size: 3rem; color: var(--primary); opacity: 0.2; margin-bottom: 20px;"></i>
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
                        <tr class="order-row" data-search="{{ strtolower(($order->product->name ?? $order->product_name) . ' ' . $order->status) }}">
                            <td style="padding-left: 25px;">
                                <span style="font-family: monospace; font-size: 0.8rem; color: var(--secondary);">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">{{ $order->product->name ?? $order->product_name }}</div>
                                <div style="font-size: 0.7rem; color: var(--secondary);">{{ $order->created_at->format('M d, Y') }}</div>
                            </td>
                            <td><span style="font-weight: 800;">x{{ $order->quantity }}</span></td>
                            <td><span style="color: #10b981; font-weight: 700;">{{ number_format($order->price * $order->quantity) }} RWF</span></td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="text-align: right; padding-right: 25px;">
                                <button class="icon-btn" onclick="viewReceipt({{ $order->id }})"><i class="fas fa-file-invoice"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 60px; text-align: center; color: var(--secondary);">
                                <i class="fas fa-history" style="font-size: 2.5rem; opacity: 0.1; margin-bottom: 15px; display: block;"></i>
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
                <div style="display: flex; gap: 20px; align-items: start; margin-bottom: 25px; background: var(--light); padding: 15px; border-radius: 15px;">
                    <img id="modalProductImage" src="" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
                    <div>
                        <h4 id="modalProductName" style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--dark);">Product Name</h4>
                        <div id="modalProductPrice" style="color: #10b981; font-weight: 700; margin-top: 5px;">0 RWF</div>
                        <div id="modalProductStock" style="font-size: 0.75rem; color: var(--secondary); margin-top: 5px;">In Stock: 0</div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--secondary); margin-bottom: 10px;">Acquisition Volume (Quantity)</label>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <input type="number" name="quantity" id="modalQuantity" class="pro-input" value="1" min="1" required style="width: 100px; text-align: center; font-size: 1.1rem; font-weight: 800;">
                        <div style="flex: 1; text-align: right;">
                            <div style="font-size: 0.7rem; color: var(--secondary); text-transform: uppercase; letter-spacing: 1px;">Estimated Investment</div>
                            <div id="estimatedTotal" style="font-size: 1.2rem; font-weight: 900; color: var(--primary);">0 RWF</div>
                        </div>
                    </div>
                </div>

                {{-- Auto-filled context for acquisition --}}
                <div style="font-size: 0.75rem; color: var(--secondary); line-height: 1.5; background: rgba(99, 102, 241, 0.05); padding: 12px; border-radius: 10px; border-left: 3px solid var(--primary);">
                    <i class="fas fa-info-circle"></i> This procurement will be registered to your primary account identifier and processed by our logistics department.
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px 30px; background: var(--light);">
                <button type="button" class="btn-cancel" onclick="closeOrderModal()" style="border: none; background: transparent;">Cancel</button>
                <button type="submit" class="action-btn btn-primary" style="width: auto; padding: 0 30px; border-radius: 14px; height: 50px; font-weight: 800;">
                    Confirm Acquisition <i class="fas fa-check"></i>
                </button>
            </div>
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentProduct = null;

    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');
        if(section === 'archive') toggleView('archive');
    });

    function toggleView(view) {
        document.getElementById('marketplaceView').style.display = view === 'marketplace' ? 'block' : 'none';
        document.getElementById('archiveView').style.display = view === 'archive' ? 'block' : 'none';
        
        document.querySelectorAll('.filter-pill').forEach(btn => {
            btn.classList.toggle('active', btn.innerText.toLowerCase().includes(view));
        });
    }

    function openOrderModal(product) {
        currentProduct = product;
        document.getElementById('modalProductId').value = product.id;
        document.getElementById('modalProductName').textContent = product.name;
        document.getElementById('modalProductPrice').textContent = new Intl.NumberFormat().format(product.selling_price) + ' RWF';
        document.getElementById('modalProductStock').textContent = 'In Stock: ' + (product.stock_quantity || 0);
        document.getElementById('modalProductImage').src = product.image ? `/storage/${product.image}` : '';
        document.getElementById('modalQuantity').value = 1;
        updateTotal();
        document.getElementById('orderModal').style.display = 'flex';
    }

    function closeOrderModal() {
        document.getElementById('orderModal').style.display = 'none';
    }

    function updateTotal() {
        const qty = document.getElementById('modalQuantity').value;
        const total = qty * currentProduct.selling_price;
        document.getElementById('estimatedTotal').textContent = new Intl.NumberFormat().format(total) + ' RWF';
    }

    document.getElementById('modalQuantity').addEventListener('input', updateTotal);

    document.getElementById('acquisitionForm').onsubmit = async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initializing...';

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const res = await response.json();
            if (res.success || response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Acquisition Finalized',
                    text: 'Your order has been registered in our logistics pipeline.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Failed', res.message || 'Validation error', 'error');
            }
        } catch (e) {
            // Fallback for non-json response or error
            location.reload();
        } finally {
            submitBtn.disabled = false;
        }
    };

    document.getElementById('marketplaceSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        // Filter products
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = card.dataset.search.includes(filter) ? '' : 'none';
        });
        // Filter orders
        document.querySelectorAll('.order-row').forEach(row => {
            row.style.display = row.dataset.search.includes(filter) ? '' : 'none';
        });
    });

    function viewReceipt(id) {
        Swal.fire({
            title: 'Technical Manifest',
            text: 'System is generating acquisition receipt. Please stand by...',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false
        });
    }

    window.onclick = (e) => {
        if (e.target === document.getElementById('orderModal')) closeOrderModal();
    }
</script>

