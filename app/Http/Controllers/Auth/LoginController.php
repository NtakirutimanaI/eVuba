<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request, \App\Services\OtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            // Bypass OTP for Admin
            if ($user->role === 'admin') {
                \Illuminate\Support\Facades\Log::info('Admin authenticated, bypassing OTP', ['email' => $user->email]);
                Auth::login($user, $request->filled('remember'));
                return redirect()->route('admin.dashboard');
            }

            \Illuminate\Support\Facades\Log::info('User authenticated, triggering OTP', ['email' => $user->email]);

            // Store email in session for OTP phase
            $request->session()->put('otp_email', $user->email);
            $request->session()->put('otp_remember', $request->filled('remember'));

            // Send OTP
            if ($otpService->sendOtp($user)) {
                \Illuminate\Support\Facades\Log::info('OTP sent successfully, redirecting');
                return redirect()->route('auth.verify-otp');
            }

            \Illuminate\Support\Facades\Log::error('OTP service failed to send');
            return back()->withErrors([
                'email' => 'Failed to send verification code. Please try again.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtpForm(Request $request)
    {
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('auth.login');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP and finalize login
     */
    public function verifyOtp(Request $request, \App\Services\OtpService $otpService)
    {
        $request->validate([
            'full_otp' => 'required|digits:6',
        ]);

        $email = $request->session()->get('otp_email');

        if ($otpService->verifyOtp($email, $request->full_otp)) {
            $user = \App\Models\User::where('email', $email)->first();

            Auth::login($user, $request->session()->get('otp_remember', false));
            $request->session()->regenerate();

            // Clean up OTP session
            $request->session()->forget(['otp_email', 'otp_remember']);

            return $this->redirectBasedOnRole($user->role);
        }

        return back()->withErrors(['otp' => 'The verification code is invalid or has expired.']);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request, \App\Services\OtpService $otpService)
    {
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('auth.login');
        }

        $user = \App\Models\User::where('email', $request->session()->get('otp_email'))->first();

        if ($otpService->sendOtp($user)) {
            return back()->with('status', 'Verification code resent successfully.');
        }

        return back()->withErrors(['email' => 'Failed to resend code.']);
    }

    /**
     * Redirect users based on role
     */
    protected function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'employee':
                return redirect()->route('employee.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
            default:
                Auth::logout();
                return redirect()->route('auth.login')->withErrors([
                    'email' => 'Your account role is not recognized.',
                ]);
        }
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    /**
     * Optional: Show registration form (if needed)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Optional: Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'customer',
        ]);

        Auth::login($user);

        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Optional: Show password reset request form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Optional: Handle password reset request
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
