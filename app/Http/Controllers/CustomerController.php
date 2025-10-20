<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Company;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get all companies for the dropdown filter
        $allCompanies = Company::select('id', 'name')->orderBy('name')->get();

        // 2. Start the query
        $query = Customer::with('company');

        // 3. Apply Company Filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 4. Apply Search Filter (for name, email, or position)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('position', 'like', $searchTerm);
            });
        }
        // 5. "show=all" is in the URL, get all records
        if ($request->input('show') === 'all') {
            $customers = $query->get();
        } else {
        // Otherwise paginate (10 per page)
            $customers =  $query->paginate(10)->appends($request->query());
        }

        // 6. Pass data to the view
        return view('customers.index', compact('customers', 'allCompanies'));
    }
    public function create()
    {
        $companies = Company::all();
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
}
