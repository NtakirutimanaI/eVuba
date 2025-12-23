@component('mail::message')
<!-- Custom Logo -->
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/logo1.png') }}" alt="E-VUBA CONNECT Logo" style="height:80px;">
</div>

<!-- Greeting -->
# Hello!

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
Reset Password
@endcomponent

This password reset link will expire in **{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes**.

If you did not request a password reset, no further action is required.

Regards,<br>
**E-VUBA CONNECT**
@endcomponent
