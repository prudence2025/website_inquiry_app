<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

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

        // Apply factory location filter if provided
        if ($request->filled('factory_location')) {
            $query->where('factory_location', $request->factory_location);
        }

        // Apply office location filter if provided
        if ($request->filled('office_location')) {
            $query->where('office_location', $request->office_location);
        }

        // If "show=all" is in the URL, get all records
        if ($request->input('show') === 'all') {
            $companies = $query->get();
        } else {
            // Otherwise paginate (10 per page)
            $companies = $query->paginate(10)->appends($request->query());
        }

        // Get data for the filter dropdowns
        $allCompanies = Company::orderBy('name')->get(['id', 'name']);
        $allIndustries = Industry::orderBy('name')->get(['id', 'name']);

        $allFactoryLocations = Company::whereNotNull('factory_location')
            ->distinct()
            ->orderBy('factory_location')
            ->pluck('factory_location')
            ->map(fn($loc) => ['id' => $loc, 'name' => $loc])
            ->values();

        $allOfficeLocations = Company::whereNotNull('office_location')
            ->distinct()
            ->orderBy('office_location')
            ->pluck('office_location')
            ->map(fn($loc) => ['id' => $loc, 'name' => $loc])
            ->values();

        // Pass all data to the view
        return view('companies.index', compact('companies', 'allCompanies', 'allIndustries', 'allFactoryLocations', 'allOfficeLocations'));
    }

    public function create()
    {
        $industries = Industry::orderBy('name')->get();
        return view('companies.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add the 'unique' rule to the 'name' field
            'name' => 'required|string|max:255|unique:companies,name',
            'industry_id' => 'required|exists:industries,id',
            'factory_location' => 'nullable|string|max:255',
            'office_location' => 'nullable|string|max:255',
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'factory_location' => $validated['factory_location'] ?? null,
            'office_location' => $validated['office_location'] ?? null,
        ]);

        $company->industries()->attach($validated['industry_id']);

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    public function edit(Company $company)
    {
        $industries = Industry::orderBy('name')->get();
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
            'factory_location' => 'nullable|string|max:255',
            'office_location' => 'nullable|string|max:255',
        ]);

        $company->update([
            'name' => $validated['name'],
            'factory_location' => $validated['factory_location'] ?? null,
            'office_location' => $validated['office_location'] ?? null,
        ]);

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

    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'industry_id' => 'required|exists:industries,id',
            'factory_location' => 'nullable|string|max:255',
            'office_location' => 'nullable|string|max:255',
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'factory_location' => $validated['factory_location'] ?? null,
            'office_location' => $validated['office_location'] ?? null,
        ]);
        $company->industries()->attach($validated['industry_id']);

        return response()->json([
            'success' => true,
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'customers' => []
            ]
        ], 201);
    }

    public function getCustomers(Company $company)
    {
        return response()->json([
            'customers' => $company->customers()->select('id', 'name')->orderBy('name')->get()
        ]);
    }

}