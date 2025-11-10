<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
       
        if ($request->has('show') && $request->input('show') === 'all') {
        // Show all industries without pagination
        $industries = Industry::latest()->get();
            } else {
        // Default paginated view (10 per page)
        $industries = Industry::latest()->paginate(10);
        }

        return view('industries.index', compact('industries'));
    }

    public function create()
    {
        return view('industries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:industries,name',
            'description' => 'nullable|string',
        ]);

        Industry::create($validated);

        return redirect()->route('industries.index')->with('success', 'Industry added successfully!');
    }

    /** 👇 Add these new methods for editing */
    public function edit(Industry $industry)
    {
        return view('industries.edit', compact('industry'));
    }

    public function update(Request $request, Industry $industry)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:industries,name,' . $industry->id,
            'description' => 'nullable|string',
        ]);

        $industry->update($validated);

        return redirect()->route('industries.index')->with('success', 'Industry updated successfully!');
    }

    public function destroy(Industry $industry)
    {
        $industry->delete();

        return redirect()->route('industries.index')->with('success', 'Industry deleted successfully!');
    }

    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:industries,name',
        ]);
    
        $industry = Industry::create($validated);
    
        return response()->json([
            'success' => true,
            'industry' => $industry,
            'message' => 'Industry created successfully!',
        ]);
    }

}
