@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="crm-container">
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Order #{{ $order->id }}</h1>
                <p class="admin-subtitle">View details for this order</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.orders.index') }}" class="add-btn"
                    style="background: var(--body-bg); color: var(--text-primary); border: 1px solid var(--header-border);">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- ORDER DETAILS CARD --}}
        <div
            style="background: var(--card-bg); border-radius: 16px; border: 1px solid var(--header-border); padding: 24px; margin-top: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">

                {{-- COLUMN 1 --}}
                <div>
                    <h3
                        style="color: var(--text-primary); margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid var(--header-border); padding-bottom: 10px;">
                        Order Information</h3>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Status</label>
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Date
                            Placed</label>
                        <div style="color: var(--text-primary); font-weight: 600;">
                            {{ $order->created_at->format('M d, Y h:i A') }}</div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Product</label>
                        <div style="color: var(--text-primary); font-weight: 600; font-size: 16px;">
                            {{ $order->product_name }}</div>
                    </div>

                    <div style="margin-bottom: 16px; display: flex; gap: 24px;">
                        <div>
                            <label
                                style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Quantity</label>
                            <div style="color: var(--text-primary); font-weight: 600;">{{ $order->quantity }}</div>
                        </div>
                        <div>
                            <label
                                style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Unit
                                Price</label>
                            <div style="color: var(--text-primary); font-weight: 600;">{{ number_format($order->price) }}
                                Rwf</div>
                        </div>
                        <div>
                            <label
                                style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Total
                                Amount</label>
                            <div style="color: var(--text-primary); font-weight: 700; font-size: 18px;">
                                {{ number_format($order->price * $order->quantity) }} Rwf</div>
                        </div>
                    </div>
                </div>

                {{-- COLUMN 2 --}}
                <div>
                    <h3
                        style="color: var(--text-primary); margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid var(--header-border); padding-bottom: 10px;">
                        Customer & Payment</h3>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Customer</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'U') }}&background=random"
                                style="width: 32px; height: 32px; border-radius: 50%;">
                            <div>
                                <div style="color: var(--text-primary); font-weight: 600;">
                                    {{ $order->user->name ?? 'Unknown User' }}</div>
                                <div style="color: var(--text-muted); font-size: 12px;">{{ $order->user->email ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Payment
                            Method</label>
                        <div style="color: var(--text-primary); font-weight: 600;">
                            @if($order->payment_method)
                                <i class="fas fa-credit-card" style="margin-right: 6px;"></i> {{ $order->payment_method }}
                            @else
                                <span style="color: var(--text-muted);">Not specified</span>
                            @endif
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label
                            style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">Payment
                            Status</label>
                        <div
                            style="font-weight: bold; color: {{ $order->payment_status == 'approved' ? 'var(--success)' : ($order->payment_status == 'pending_approval' ? 'var(--warning)' : 'var(--text-muted)') }};">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </div>
                        @if($order->transaction_ref)
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Ref:
                                {{ $order->transaction_ref }}</div>
                        @endif
                    </div>

                    {{-- MANAGEMENT ACTIONS --}}
                    <div style="margin-top: 32px; padding-top: 20px; border-top: 1px solid var(--header-border);">
                        <label
                            style="display: block; color: var(--text-muted); font-size: 12px; margin-bottom: 10px;">Manage
                            Order</label>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            @if($order->payment_status == 'pending_approval')
                                <form method="POST" action="{{ route('admin.orders.approve_payment', $order->id) }}">
                                    @csrf
                                    <button type="submit" class="action-btn"
                                        style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 8px 16px; width: auto; height: auto;">
                                        <i class="fas fa-check-double" style="margin-right: 6px;"></i> Approve Payment
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.orders.send_invoice', $order->id) }}">
                                @csrf
                                <button type="submit" class="action-btn"
                                    style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 8px 16px; width: auto; height: auto;">
                                    <i class="fas fa-file-invoice" style="margin-right: 6px;"></i> Send Invoice
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}"
                                style="display: flex; gap: 10px;">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    style="padding: 8px; border-radius: 6px; border: 1px solid var(--header-border); font-size: 13px; background: var(--body-bg); color: var(--text-primary); cursor: pointer;">
                                    @foreach(['pending', 'processing', 'approved', 'completed', 'cancelled'] as $st)
                                        <option value="{{ $st }}" {{ $order->status == $st ? 'selected' : '' }}>
                                            Change Status: {{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection