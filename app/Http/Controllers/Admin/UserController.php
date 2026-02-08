<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use App\Exports\UsersExport;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /* ========================== USERS CRUD ========================== */

    public function index(Request $request)
    {
        $query = User::orderBy('id', 'desc');

        // ====== Date filter ======
        if ($request->filled(['from_date', 'to_date'])) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        // ====== Search filter ======
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("IF(email_verified_at IS NOT NULL, 'Verified', 'Pending') LIKE ?", ["%{$search}%"]);
            });
        }

        $users = $query->paginate(10)->withQueryString();

        $users->getCollection()->transform(function ($user) {
            $user->role = $user->role ?? 'N/A';
            return $user;
        });

        $currentUser = Auth::user();
        $roleIds = $currentUser->roles->pluck('id')->toArray();

        // ====== Stats for Cards ======
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        /* ========================== PERMISSIONS ========================== */
        $permissions = DB::table('role_page_permission')
            ->join('pages', 'role_page_permission.page_id', '=', 'pages.id')
            ->join('permissions', 'role_page_permission.permission_id', '=', 'permissions.id')
            ->whereIn('role_page_permission.role_id', $roleIds)
            ->selectRaw('pages.name as page_name, GROUP_CONCAT(permissions.name) as actions')
            ->groupBy('pages.name')
            ->pluck('actions', 'page_name')
            ->toArray();


        foreach ($permissions as $page => $actions) {
            $permissions[$page] = explode(',', $actions);
        }

        return view('admin.users.index', compact('users', 'permissions', 'stats'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'nullable|exists:roles,name',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        if ($request->role) {
            $user->assignRole($request->role);
            $user->role = $request->role;
        } else {
            $user->role = 'N/A';
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->role = $user->role ?? 'N/A';
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->role = $user->role ?? 'N/A';
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'nullable|exists:roles,name',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->role) {
            $user->syncRoles([$request->role]);
            $user->role = $request->role;
        } else {
            $user->role = 'N/A';
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }

    /* ========================== REPORTS ========================== */

    public function report(Request $request)
    {
        // Default to current month if dates are missing
        if (!$request->has('from_date')) {
            $request->merge(['from_date' => Carbon::now()->startOfMonth()->format('Y-m-d')]);
        }
        if (!$request->has('to_date')) {
            $request->merge(['to_date' => Carbon::now()->endOfMonth()->format('Y-m-d')]);
        }

        $users = $this->getUsersByDate($request)->get();
        return view('admin.users.report', compact('users', 'request'));
    }

    public function reportPdf(Request $request)
    {
        $this->validateDates($request);
        $users = $this->getUsersByDate($request)->get();
        $pdf = PDF::loadView('admin.users.report_pdf', compact('users', 'request'));
        return $pdf->download('users_report.pdf');
    }

    public function reportExcel(Request $request)
    {
        $this->validateDates($request);
        return Excel::download(
            new UsersExport($request->from_date, $request->to_date),
            'users_report.xlsx'
        );
    }

    /* ========================== EMAIL ========================== */

    public function sendReportEmail(Request $request)
    {
        $this->validateDates($request);
        $users = $this->getUsersByDate($request)->get();
        $pdf = PDF::loadView('admin.users.report_pdf', compact('users', 'request'));

        Mail::send([], [], function ($message) use ($request, $pdf) {
            $message->to($request->email)
                ->subject('User Report')
                ->attachData($pdf->output(), 'users_report.pdf');
        });

        return back()->with('success', 'User report emailed successfully.');
    }

    /* ========================== HELPERS ========================== */

    private function validateDates(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
    }

    private function getUsersByDate(Request $request)
    {
        return User::whereBetween('created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ])->orderBy('created_at', 'asc');
    }
}
