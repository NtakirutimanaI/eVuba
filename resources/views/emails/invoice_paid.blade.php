@component('mail::message')
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td align="left">
            <span style="background-color: #00C853; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-family: sans-serif; font-size: 16px;">Receipt</span>
        </td>
        <td align="right">
            <span style="color: #0056b3; font-weight: bold; font-size: 20px; font-family: sans-serif;">eVubaConnect</span>
        </td>
    </tr>
</table>

# Invoice for Order #{{ $order->id }}

Dear {{ $order->user->name }},

Thank you . Your payment for **{{ $order->product_name }}** has been processed.

**Order Details:**
- **Product:** {{ $order->product_name }}
- **Quantity:** {{ $order->quantity }}
- **Subtotal:** {{ number_format(($order->quantity * $order->price) / 1.18, 2) }} FRW
- **VAT (18%):**
{{ number_format(($order->quantity * $order->price) - (($order->quantity * $order->price) / 1.18), 2) }} FRW
- **Total Amount:** {{ number_format($order->quantity * $order->price) }} FRW
- **Payment Method:** {{ $order->payment_method }}
- **Transaction Ref:** {{ $order->transaction_ref }}
- **Date:** {{ $order->created_at->format('M d, Y') }}

@component('mail::button', ['url' => route('customer.orders.index')])
View Order
@endcomponent

Thanks,<br>
Powered By eVubaConnect
@endcomponent