<h3>Stock Alert Notification</h3>
<p><strong>Product:</strong> {{ $stock->product->name }}</p>
<p><strong>Quantity:</strong> {{ $stock->quantity }}</p>
<p><strong>Type:</strong> {{ ucfirst($stock->type) }}</p>
<p><strong>Supplier:</strong> {{ $stock->supplier->name ?? 'N/A' }}</p>
<p><strong>Date:</strong> {{ $stock->stock_in_date->format('Y-m-d') }}</p>

<p>Please check your inventory system for more details.</p>
