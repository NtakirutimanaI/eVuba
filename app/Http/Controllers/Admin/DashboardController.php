<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        // --- Summary Counts ---
        $usersCount = User::count();
        $employeesCount = Employee::count();
        $customersCount = Customer::count();
        $ordersCount = Order::count();
        $supportCount = Ticket::count();

        // --- Sales Stats ---
        $totalSalesAmount = Sale::sum('total_amount');
        $now = Carbon::now();

        $thisMonthSales = Sale::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_amount');

        $lastMonthSales = Sale::whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->sum('total_amount');

        $salesGrowth = $lastMonthSales > 0
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : ($thisMonthSales > 0 ? 100 : 0);

        // --- Inventory: Low Stock Items ---
        // Stock is tracked in the `stock` table (type: 'in' / 'out').
        // Net stock = SUM(in) - SUM(out). Count products where net stock < 10.
        $lowStockCount = DB::table('products')
            ->whereRaw('(
                COALESCE((SELECT SUM(quantity) FROM stock WHERE product_id = products.id AND type = "in"), 0)
                -
                COALESCE((SELECT SUM(quantity) FROM stock WHERE product_id = products.id AND type = "out"), 0)
            ) < 10')
            ->count();

        // --- Recent Records ---
        $recentUsers = User::latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();
        $recentOrders = Order::with('customer.user')->latest()->take(5)->get();
        $recentSupport = Ticket::with(['customer', 'category'])->latest()->take(5)->get();
        $recentSales = Sale::with(['product', 'customer'])->latest()->take(5)->get();

        // --- Customers by Month (Last 6 Months) ---
        $customersByMonth = $this->buildMonthlyChart(
            Customer::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $now->copy()->subMonths(6))
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get(),
            'month',
            'count'
        );

        // --- Orders Over Time (Last 14 Days) ---
        $ordersOverTime = $this->buildDailyChart(
            Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $now->copy()->subDays(14))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get(),
            'date',
            'count'
        );

        // --- Revenue Over Time (Last 14 Days) ---
        $revenueOverTime = $this->buildDailyChart(
            Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->where('created_at', '>=', $now->copy()->subDays(14))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get(),
            'date',
            'total',
            true
        );

        // --- Support Status Distribution ---
        $supportStatusData = Ticket::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $supportStatus = [
            'labels' => $supportStatusData->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
            'data' => $supportStatusData->pluck('total')->toArray(),
        ];

        // --- Top Selling Products (by quantity sold) ---
        $topProducts = Sale::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
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

    /**
     * Build a labeled monthly chart dataset from a query result collection.
     */
    private function buildMonthlyChart($rows, string $monthKey, string $valueKey): array
    {
        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $labels[] = Carbon::create()->month($row->{$monthKey})->format('F');
            $data[] = $row->{$valueKey};
        }

        return compact('labels', 'data');
    }

    /**
     * Build a labeled daily chart dataset from a query result collection.
     */
    private function buildDailyChart($rows, string $dateKey, string $valueKey, bool $isFloat = false): array
    {
        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $labels[] = Carbon::parse($row->{$dateKey})->format('M d');
            $data[] = $isFloat ? (float) $row->{$valueKey} : $row->{$valueKey};
        }

        return compact('labels', 'data');
    }
}
