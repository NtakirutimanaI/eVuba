<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ManagerCustomerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('logout')->with('error', 'No accessible pages');
        }

        $customers = Customer::orderBy('id', 'desc')->get();

        // Client Intelligence Stats
        $stats = [
            'total_entities' => Customer::count(),
            'new_acquisitions' => Customer::where('created_at', '>=', now()->startOfMonth())->count(),
            'active_regions' => Customer::whereNotNull('address')->distinct('address')->count(),
            'recent_activity' => Customer::where('updated_at', '>=', now()->subDays(7))->count(),
        ];

        return view('manager.customer.index', compact('user', 'customers', 'stats'));
    }

    public function create()
    {
        return view('manager.customer.create');
    }

    public function store(Request $request)
    {
        // VALIDATION
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255|unique:customers,email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // FAILED VALIDATION
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer = Customer::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);

        } catch (QueryException $e) {

            if ($e->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already exists.'
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('manager.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:customers,email,' . $customer->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($request->all());

        return redirect()->route('manager.customer.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('manager.customer.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
