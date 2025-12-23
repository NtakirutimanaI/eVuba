<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $userRole = strtolower(Auth::user()->role);
        $requiredRole = strtolower($role);

        if ($userRole !== $requiredRole) {
            switch ($userRole) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'manager':
                    return redirect()->route('manager.dashboard');
                case 'employee':
                    return redirect()->route('employee.dashboard');
                case 'customer':
                    return redirect()->route('customer.dashboard');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
}
