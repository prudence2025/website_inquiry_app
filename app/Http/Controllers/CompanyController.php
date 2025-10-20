<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import the Rule class

class CompanyController extends Controller
{
public function index(Request $request)
    {
        // Start a query builder instance
        $query = Company::with('industries')->latest();

        // Apply company filter if provided
        if ($request->filled('company_id')) {
            $query->where('id', $request->company_id);
        }

        // Apply industry filter if provided (using a relationship query)
        if ($request->filled('industry_id')) {
            $query->whereHas('industries', function ($q) use ($request) {
                $q->where('industries.id', $request->industry_id);
            });
        }

         // If "show=all" is in the URL, get all records
        if ($request->input('show') === 'all') {
            $companies = $query->get();
        } else {
            // Otherwise paginate (10 per page)
            $companies =  $query->paginate(10)->appends($request->query());
        }

        // Get data for the filter dropdowns
        $allCompanies = Company::orderBy('name')->get(['id', 'name']);
        $allIndustries = Industry::orderBy('name')->get(['id', 'name']);

        // Pass all data to the view
        return view('companies.index', compact('companies', 'allCompanies', 'allIndustries'));
    }

    public function create()
    {
        $industries = Industry::all();
        return view('companies.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add the 'unique' rule to the 'name' field
            'name' => 'required|string|max:255|unique:companies,name',
            'industry_id' => 'required|exists:industries,id',
        ]);
    
        $company = Company::create(['name' => $validated['name']]);
    
        $company->industries()->attach($validated['industry_id']);
    
        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    public function edit(Company $company)
    {
        $industries = Industry::all();
        // The variable name was changed in the previous step, let's keep it consistent
        $selectedIndustry = $company->industries->first()?->id;
        return view('companies.edit', compact('company', 'industries', 'selectedIndustry'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            // This rule ensures the name is unique, EXCEPT for the current company
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($company->id),
            ],
            // Kept validation consistent with the store method
            'industry_id' => 'required|exists:industries,id',
        ]);

        $company->update(['name' => $validated['name']]);

        // Sync the single industry
        $company->industries()->sync([$validated['industry_id']]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        $company->industries()->detach();
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }
}