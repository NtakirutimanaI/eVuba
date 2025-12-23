<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Enterprise Inventory Audit - eVuba</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; }
        .header { border-bottom: 3px solid #6366f1; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #1e1b4b; margin: 0; font-size: 26px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { color: #64748b; margin: 5px 0 0; font-size: 14px; }
        .meta-grid { width: 100%; margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: 12px; }
        .meta-grid td { padding: 5px 0; font-size: 12px; color: #475569; }
        .meta-label { font-weight: bold; color: #1e293b; width: 120px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; border-radius: 8px; overflow: hidden; }
        th { background-color: #f1f5f9; color: #475569; font-weight: bold; text-align: left; padding: 12px 15px; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        td { padding: 12px 15px; font-size: 11px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; padding: 20px 0; border-top: 1px solid #f1f5f9; }
        .brand-accent { color: #6366f1; }
        .stamp { text-align: right; margin-top: 50px; }
        .stamp img { width: 120px; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Inventory <span class="brand-accent">Catalog</span> Audit</h1>
        <p>Institutional Product Registry & Asset Mapping</p>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-label">Generated On:</td>
            <td>{{ now()->format('M d, Y H:i') }}</td>
            <td class="meta-label">Authority:</td>
            <td>{{ auth()->user()->name ?? 'System Manager' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Auditor Scope:</td>
            <td>Global Product Repository</td>
            <td class="meta-label">Entity:</td>
            <td>eVuba Solutions Hub</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>REF</th>
                <th>Identifier/Name</th>
                <th>Category Cluster</th>
                <th>Description / Specification</th>
                <th>Registry Timeline</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>#{{ $product->id }}</td>
                    <td style="font-weight: bold;">{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'GENERAL' }}</td>
                    <td style="color: #64748b; font-size: 10px;">{{ $product->description ?? 'No specific technical notes registered.' }}</td>
                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">No product entities found in registry.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="stamp">
        <div style="font-size: 10px; color: #94a3b8; margin-bottom: 10px;">AUTHORISED REGISTRY STAMP</div>
        @if(file_exists(public_path('images/stamp.png')))
            <img src="{{ public_path('images/stamp.png') }}" alt="Authorized Stamp">
        @else
            <div style="width: 120px; height: 60px; border: 2px dashed #e2e8f0; display: inline-block;"></div>
        @endif
    </div>

    <div class="footer">
        This document is an official inventory record. Generated via eVuba Intelligence Dashboard. &copy; {{ date('Y') }} eVuba.
    </div>
</body>
</html>
