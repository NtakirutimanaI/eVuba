<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

// Excel & PDF
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('logout')->with('error', 'No accessible pages');
        }

        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.customer.index', compact('user', 'customers'));
    }

    public function create() { return view('admin.customer.create'); }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        try {
            $customer = Customer::create($request->only('name','email','phone','address'));
            return response()->json(['success'=>true,'customer'=>$customer]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['success'=>false,'message'=>'Email already exists.'],409);
            }
            return response()->json(['success'=>false,'message'=>'Database error: '.$e->getMessage()],500);
        }
    }

    public function edit($id) { 
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($request->all());
        return redirect()->route('admin.customers.index')->with('success','Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success','Customer deleted successfully.');
    }

    // ===================== REPORT METHOD =====================
    public function report(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'nullable|string'
        ]);

        $from = $request->from_date;
        $to = $request->to_date;

        $customers = Customer::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
                             ->orderBy('id','desc')->get();

        if ($request->type === 'excel') {
            return Excel::download(new CustomersExport($from,$to), 'customers_report.xlsx');
        }

        $pdf = Pdf::loadView('admin.customer.report_pdf', compact('customers','from','to'));
        return $pdf->download('customers_report.pdf');
    }

    // ===================== EMPTY SHOW METHOD =====================
    public function show($id) {
        return redirect()->route('admin.customers.index');
    }
}
