@component('mail::message')
# Invoice for Order #{{ $order->id }}

Dear {{ $order->user->name }},

Thank you . Your payment for **{{ $order->product_name }}** has been processed.

**Order Details:**
- **Product:** {{ $order->product_name }}
- **Quantity:** {{ $order->quantity }}
- **Total Amount:** {{ number_format($order->quantity * $order->price) }} RWF
- **Payment Method:** {{ $order->payment_method }}
- **Transaction Ref:** {{ $order->transaction_ref }}
- **Date:** {{ $order->created_at->format('M d, Y') }}

@component('mail::button', ['url' => route('customer.orders.index')])
View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent