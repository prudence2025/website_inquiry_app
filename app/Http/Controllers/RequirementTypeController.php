<?php

namespace App\Http\Controllers;

use App\Models\RequirementType;
use Illuminate\Http\Request;

class RequirementTypeController extends Controller
{
    public function index (Request $request)
    {
        //$types = RequirementType::latest()->get();

            if ($request->input('show') === 'all') {
        $types = RequirementType::latest()->get();
    } else {
        // Otherwise paginate (10 per page)
        $types = RequirementType::latest()->paginate(10);
    }
        return view('requirement-types.index', compact('types'));
    }

    public function create()
    {
        return view('requirement-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:requirement_types,name',
            'description' => 'nullable|string',
        ]);

        RequirementType::create($validated);

        return redirect()->route('requirement-types.index')->with('success', 'Requirement type added successfully!');
    }

    public function edit(RequirementType $requirementType)
    {
        return view('requirement-types.edit', compact('requirementType'));
    }

    public function update(Request $request, RequirementType $requirementType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:requirement_types,name,' . $requirementType->id,
            'description' => 'nullable|string',
        ]);

        $requirementType->update($validated);

        return redirect()->route('requirement-types.index')->with('success', 'Requirement type updated successfully!');
    }

    public function destroy(RequirementType $requirementType)
    {
        $requirementType->delete();
        return redirect()->route('requirement-types.index')->with('success', 'Requirement type deleted successfully!');
    }
}
