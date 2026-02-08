<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Totals
        $usersCount = User::count();
        $employeesCount = Employee::count();
        $customersCount = Customer::count();
        $ordersCount = Order::count();
        $supportCount = Ticket::count();

        // Sales Stats
        $totalSalesAmount = Sale::sum('total_amount');
        $thisMonthSales = Sale::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $lastMonthSales = Sale::whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('total_amount');
        $salesGrowth = $lastMonthSales > 0 ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Inventory Stats
        $lowStockCount = Product::all()->filter(function ($product) {
            return $product->remaining_stock < 10;
        })->count();

        // Recent Records
        $recentUsers = User::latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();
        $recentOrders = Order::with('customer.user')->latest()->take(5)->get();
        $recentSupport = Ticket::with(['customer', 'category'])->latest()->take(5)->get();
        $recentSales = Sale::with(['product', 'customer'])->latest()->take(5)->get();

        // Customers by Month (Last 6 Months)
        $customersByMonthData = Customer::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $customersByMonth = ['labels' => [], 'data' => []];
        foreach ($customersByMonthData as $row) {
            $customersByMonth['labels'][] = Carbon::create()->month($row->month)->format('F');
            $customersByMonth['data'][] = $row->count;
        }

        // Orders Over Time (Last 14 Days)
        $ordersOverTimeData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $ordersOverTime = ['labels' => [], 'data' => []];
        foreach ($ordersOverTimeData as $row) {
            $ordersOverTime['labels'][] = Carbon::parse($row->date)->format('M d');
            $ordersOverTime['data'][] = $row->count;
        }

        // Revenue Over Time (Last 14 Days)
        $revenueOverTimeData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueOverTime = ['labels' => [], 'data' => []];
        foreach ($revenueOverTimeData as $row) {
            $revenueOverTime['labels'][] = Carbon::parse($row->date)->format('M d');
            $revenueOverTime['data'][] = (float) $row->total;
        }

        // Support Status Distribution
        $supportStatusData = Ticket::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $supportStatus = ['labels' => [], 'data' => []];
        foreach ($supportStatusData as $row) {
            $supportStatus['labels'][] = ucfirst(str_replace('_', ' ', $row->status));
            $supportStatus['data'][] = $row->total;
        }

        // Top Selling Products
        $topProducts = Sale::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->with('product')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'employeesCount',
            'customersCount',
            'ordersCount',
            'supportCount',
            'totalSalesAmount',
            'salesGrowth',
            'lowStockCount',
            'recentUsers',
            'recentEmployees',
            'recentOrders',
            'recentSupport',
            'recentSales',
            'customersByMonth',
            'ordersOverTime',
            'revenueOverTime',
            'supportStatus',
            'topProducts'
        ));
    }
}
