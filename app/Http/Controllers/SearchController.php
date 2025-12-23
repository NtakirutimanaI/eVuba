<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Appointment; // Assuming this is for tasks/activities
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        if (!$query) {
            return response()->json(['results' => []]);
        }

        $results = [];
        $role = Auth::user()->role;

        // Search Orders
        if (in_array($role, ['admin', 'manager', 'customer'])) {
            $orderQuery = Order::where('product_name', 'like', "%{$query}%");
            if ($role === 'customer') {
                $orderQuery->where('user_id', Auth::user()->id);
            }
            $orders = $orderQuery->limit(5)->get();
            foreach ($orders as $o) {
                $results[] = [
                    'type' => 'Order',
                    'title' => "Order: {$o->product_name}",
                    'url' => Route::has($role . '.orders.index') ? route($role . '.orders.index') : '#',
                    'icon' => 'fas fa-shopping-cart'
                ];
            }
        }

        // Search Customers
        if (in_array($role, ['admin', 'manager'])) {
            $customers = Customer::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(5)->get();
            foreach ($customers as $c) {
                // Route for admin is admin.customers.index. If manager doesn't have one, fallback to dash
                $route = ($role == 'admin') ? 'admin.customers.index' : ($role . '.dashboard');
                $results[] = [
                    'type' => 'Customer',
                    'title' => $c->name,
                    'url' => Route::has($route) ? route($route) : '#',
                    'icon' => 'fas fa-user-friends'
                ];
            }
        }

        // Search Products
        if (in_array($role, ['admin', 'manager'])) {
            $products = Product::where('name', 'like', "%{$query}%")
                ->limit(5)->get();
            foreach ($products as $p) {
                $route = $role . '.product.index';
                $results[] = [
                    'type' => 'Product',
                    'title' => $p->name,
                    'url' => Route::has($route) ? route($route) : '#',
                    'icon' => 'fas fa-box'
                ];
            }
        }

        // Search Tasks/Appointments
        if (in_array($role, ['admin', 'manager', 'employee'])) {
            $tasks = Appointment::where('title', 'like', "%{$query}%")
                ->limit(5)->get();
            foreach ($tasks as $t) {
                $route = ($role == 'manager') ? 'manager.appointments' : $role . '.appointments.index';
                $results[] = [
                    'type' => 'Task',
                    'title' => $t->title,
                    'url' => Route::has($route) ? route($route) : '#',
                    'icon' => 'fas fa-clipboard-list'
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
