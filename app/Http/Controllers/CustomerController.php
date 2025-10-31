<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get all companies for the dropdown filter
        $allCompanies = Company::select('id', 'name')->orderBy('name')->get();
    
        // 2. Start the query and eagerly load the 'company' relation
        $query = Customer::with('company');
    
        // 3. ⭐ ADD THIS LINE: Order by latest records first
        $query->latest(); // This is shorthand for orderBy('created_at', 'desc')
    
        // 4. Apply Company Filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
    
        // 5. Apply Search Filter (for name, email, or position)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('position', 'like', $searchTerm);
            });
        }
    
        // 6. Determine the final result set (All or Paginated)
        if ($request->input('show') === 'all') {
            $customers = $query->get();
        } else {
            // Otherwise paginate (10 per page) and keep query string parameters
            $customers = $query->paginate(10)->appends($request->query());
        }
    
        // 7. Pass data to the view
        return view('customers.index', compact('customers', 'allCompanies'));
    }

    public function create()
    {
        $companies = Company::orderBy('created_at', 'desc')->get();
        return view('customers.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function edit(Customer $customer)
    {
        $companies = Company::all();
        return view('customers.edit', compact('customer', 'companies'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
    
        $customer = Customer::create($validated);
    
        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'company_id' => $customer->company_id,
                'company_name' => $customer->company->name ?? null,
            ]
        ], 201);
    }
    
}
