@extends('layouts.app')

@section('title', 'My Documents')

@section('content')
    <div class="crm-container">
        <div class="crm-header">
            <div>
                <h1 class="crm-title">Documents</h1>
                <p class="crm-subtitle">Access your invoices and receipts.</p>
            </div>
        </div>

        <div class="crm-content">
            @if($documents->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-file-invoice fa-3x" style="color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <p>No documents found.</p>
                    <a href="{{ route('customer.orders.index') }}" class="add-btn" style="margin-top: 1rem;">
                        Place an Order
                    </a>
                </div>
            @else
                <div class="crm-table-container" style="background: transparent; border: none; box-shadow: none;">
                    <div
                        style="display: grid; grid-template-columns: 2fr 3fr 2fr 1fr; padding: 15px 20px; font-weight: 700; color: var(--text-heading); border-bottom: 2px solid var(--header-border);">
                        <div>Application No</div>
                        <div>Service</div>
                        <div>Issue Date</div>
                        <div style="text-align: right;">Actions</div>
                    </div>

                    @foreach($documents as $doc)
                        <div
                            style="display: grid; grid-template-columns: 2fr 3fr 2fr 1fr; align-items: center; background: var(--card-bg); margin-top: 10px; padding: 20px; border: 1px solid var(--header-border); border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s ease;">

                            {{-- Application No --}}
                            <div style="font-weight: 500; color: var(--text-primary);">
                                {{ $doc->transaction_ref ?? 'B' . str_pad($doc->id, 10, '0', STR_PAD_LEFT) }}
                            </div>

                            {{-- Service --}}
                            <div style="display: flex; align-items: center; gap: 10px; color: #3b82f6;">
                                <i class="far fa-file-alt" style="font-size: 16px;"></i>
                                <span style="color: var(--text-primary); font-weight: 500;">
                                    Invoice for {{ $doc->product_name }}
                                </span>
                            </div>

                            {{-- Issue Date --}}
                            <div style="color: var(--text-primary);">
                                {{ $doc->created_at->format('n/j/y, g:i A') }}
                            </div>

                            {{-- Actions --}}
                            <div style="text-align: right; display: flex; gap: 15px; justify-content: flex-end;">
                                <a href="{{ route('customer.documents.invoice', $doc->id) }}" title="Download"
                                    style="color: var(--text-muted); font-size: 18px; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--primary)'"
                                    onmouseout="this.style.color='var(--text-muted)'">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('customer.orders.index') }}" title="Refresh/View"
                                    style="color: var(--text-muted); font-size: 18px; transition: color 0.2s;"
                                    onmouseover="this.style.color='var(--primary)'"
                                    onmouseout="this.style.color='var(--text-muted)'">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="crm-pagination" style="margin-top: 20px;">
                    {{ $documents->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Dark Mode Support for this specific page if needed, mimicking global */
        [data-theme="dark"] .empty-state p {
            color: var(--text-muted);
        }

        .empty-state {
            // Center empty state
            text-align: center;
            padding: 4rem 1rem;
        }
    </style>
@endsection