<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Industry;
use App\Models\User;
use App\Models\RequirementType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InquiryController extends Controller
{
     // helper to apply filters to a query builder
    protected function applyFilters($query, Request $request)
    {
        // date range
        if ($request->filled('date_from')) {
            $from = Carbon::parse($request->input('date_from'))->startOfDay();
            $query->where('inquiry_date', '>=', $from);
        }
        if ($request->filled('date_to')) {
            $to = Carbon::parse($request->input('date_to'))->endOfDay();
            $query->where('inquiry_date', '<=', $to);
        }

        // requirement type (we store the name)
        if ($request->filled('requirement_type')) {
            $query->where('requirement_type', $request->input('requirement_type'));
        }

        // process level / status
        if ($request->filled('process_level')) {
            $query->where('process_level', $request->input('process_level'));
        }

        // company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        return $query;
    }

public function index(Request $request)
{
    // Load filter dropdown data
    $companies = Company::select('id', 'name')->get();
    $requirementTypes = RequirementType::select('name')->get()->unique('name')->values();
    $processLevels = [
        'Received',
        'Quoted',
        'Discussing',
        'Settled',
        'Dropped'
    ];

    // Convert to plain arrays for Alpine <=> @js()
    $allCompanies = $companies->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values();
    $allRequirementTypes = $requirementTypes->map(fn($r) => ['id' => $r->name, 'name' => $r->name])->values();

    // Base query
    $query = Inquiry::with(['customer', 'company.industries'])->latest();

    // Apply filters
    if ($request->filled('date_from')) {
        $query->whereDate('inquiry_date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('inquiry_date', '<=', $request->date_to);
    }
    if ($request->filled('requirement_type')) {
        $query->where('requirement_type', $request->requirement_type);
    }
    if ($request->filled('process_level')) {
        $query->where('process_level', $request->process_level);
    }
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    // Export CSV
    if ($request->filled('export') && $request->export === 'csv') {
        $inquiries = $query->get();
        return $this->streamCsvDownload($inquiries);
    }

    // ✅ Paginate (10 per page)
    $inquiries = $query->paginate(10)->appends($request->query());

    return view('inquiries.index', compact(
        'inquiries',
        'allCompanies',
        'allRequirementTypes',
        'processLevels'
    ));
}

    // Optionally separate export route (not required). This uses same filters.
    public function export(Request $request)
    {
        $query = Inquiry::with(['customer', 'company.industries'])->latest();
        $this->applyFilters($query, $request);

        $inquiries = $query->get();

        return $this->streamCsvDownload($inquiries);
    }

    protected function streamCsvDownload($inquiries)
{
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="inquiries-export-' . now()->format('Y-m-d') . '.csv"',
    ];

    $callback = function () use ($inquiries) {
        $file = fopen('php://output', 'w');
        fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add BOM for Excel UTF-8 compatibility

        // ✅ Updated Header Row
        fputcsv($file, [
            'ID',
            'Date',
            'Requirement Type',
            'Receiver',
            'Company',
            'Industry',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Status',
            'Amount',
            'More Info'
        ]);

        foreach ($inquiries as $inquiry) {
            $industry = $inquiry->company && $inquiry->company->industries->isNotEmpty()
                ? $inquiry->company->industries->first()->name
                : '';

            // ✅ Safely access customer details
            $customerName = $inquiry->customer->name ?? '';
            $customerEmail = $inquiry->customer->email ?? '';
            $customerPhone = $inquiry->customer->phone ?? '';

            fputcsv($file, [
                $inquiry->id,
                $inquiry->inquiry_date,
                $inquiry->requirement_type,
                $inquiry->receiver_name,
                $inquiry->company->name ?? '',
                $industry,
                $customerName,
                $customerEmail,
                $customerPhone,
                $inquiry->process_level,
                $inquiry->amount,
                $inquiry->more_info,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

   public function create()
    {
        $companies = Company::with('customers:id,name,company_id')
                        ->orderBy('created_at', 'desc')
                        ->get(['id', 'name']);
        $requirementTypes = RequirementType::orderBy('name')->get(['id', 'name']);
        $receivers = User::where('id', '!=', 1)
                   ->orderBy('name')
                   ->pluck('name');
        $processLevels = [
            'Received',
            'Quoted',
            'Discussing',
            'Settled',
            'Dropped'
        ];

        $companyOptions = $companies->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'customers' => $c->customers->map(fn($cu) => [
                    'id' => $cu->id,
                    'name' => $cu->name
                ])->values()
            ];
        });

        return view('inquiries.create', compact(
            'companies',
            'companyOptions',
            'requirementTypes',
            'receivers',
            'processLevels'
        ));
    }



    public function store(Request $request)
    {   
        //dd($request->all());
        $validated = $request->validate([
            'inquiry_date' => 'required|date',
            'receiver_name' => 'required|string|max:255',
            'requirement_type' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'customer_id' => 'nullable|exists:customers,id',
            'more_info' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'process_level' => 'required|string|max:255',
        ]);

        Inquiry::create($validated);

        return redirect()->route('inquiries.index')->with('success', 'Inquiry added successfully!');
    }

public function edit(Inquiry $inquiry)
{
    // load companies along with their customers (only needed fields)
    $companies = Company::with(['customers' => function ($q) {
        $q->select('id', 'name', 'company_id');
    }])->get(['id', 'name']);

    $requirementTypes = RequirementType::orderBy('name')->get(['id', 'name']);
    $receivers = User::where('id', '!=', 1)
                   ->orderBy('name')
                   ->pluck('name');
    $processLevels = [
        'Received',
        'Quoted',
        'Discussing',
        'Settled',
        'Dropped'
    ];

    return view('inquiries.edit', compact(
        'inquiry',
        'companies',
        'requirementTypes',
        'receivers',
        'processLevels'
    ));
}


public function update(Request $request, Inquiry $inquiry)
{ 
    $validated = $request->validate([
        'inquiry_date' => 'required|date',
        'receiver_name' => 'required|string|max:255',
        'requirement_type' => 'required|string|max:255',
        'company_id' => 'nullable|exists:companies,id',
        'customer_id' => 'nullable|exists:customers,id',
        'more_info' => 'nullable|string',
        'amount' => 'nullable|numeric',
        'process_level' => 'required|string|max:255',
    ]);

    $inquiry->update($validated);

    return redirect()->route('inquiries.index')->with('success', 'Inquiry updated successfully!');
}

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('inquiries.index')->with('success', 'Inquiry deleted successfully!');
    }
}
