<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard page (initial load).
     * The blade will fetch chart & counts via AJAX (stats() below) but we can pre-populate with default data too if needed.
     */
    public function index(Request $request)
{

    // Default range: last 30 days
    $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : Carbon::now()->endOfDay();
    $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : (clone $to)->subDays(29)->startOfDay();

    // Meta totals
    $totalInquiries = Inquiry::count();
    $totalCompanies = Company::count();
    $totalCustomers = Customer::count();

    // Status counts (matching your view)
    $statusLabels = ['Received', 'Quoted', 'Discussing', 'Settled', 'Dropped'];
    $statusCounts = [];
    foreach ($statusLabels as $label) {
        $statusCounts[$label] = Inquiry::where('process_level', $label)->count();
    }

    // Inquiries in date range
    $rangeInquiries = Inquiry::whereBetween('inquiry_date', [$from->toDateString(), $to->toDateString()])->count();

    // Build chart dataset
    $period = [];
    $cursor = $from->copy();
    while ($cursor->lte($to)) {
        $period[] = $cursor->toDateString();
        $cursor->addDay();
    }

    $rows = Inquiry::selectRaw('DATE(inquiry_date) as date, COUNT(*) as cnt')
        ->whereBetween('inquiry_date', [$from->toDateString(), $to->toDateString()])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('cnt', 'date')
        ->toArray();

    $series = [];
    foreach ($period as $d) {
        $series[] = $rows[$d] ?? 0;
    }

    // Pass everything to Blade
    return view('dashboard', [
        'totalInquiries' => $totalInquiries,
        'totalCompanies' => $totalCompanies,
        'totalCustomers' => $totalCustomers,
        'rangeInquiries' => $rangeInquiries,
        'statusCounts'   => $statusCounts,
        'labels'         => $period,
        'series'         => $series,
        'from'           => $from->toDateString(),
        'to'             => $to->toDateString(),
    ]);
}


    /**
     * Return JSON with counts and per-day series for a date range.
     * Query params:
     *  - from (YYYY-MM-DD)
     *  - to   (YYYY-MM-DD)
     *
     * Defaults to last 30 days.
     */
    public function stats(Request $request)
    {
        // Default date range: last 30 days (inclusive)
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : Carbon::now()->endOfDay();
        $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : (clone $to)->subDays(29)->startOfDay();

        // normalize to dates only
        $fromDate = $from->toDateString();
        $toDate = $to->toDateString();

        // Total counts
        $totalInquiries = Inquiry::count();
        $totalCompanies = Company::count();
        $totalCustomers = Customer::count();

        // Status mapping (your readable names -> DB process_level values)
        $statusMap = [
            'Received'   => 'Just received the inquiry',
            'Quoted'     => 'Quotation sent',
            'Discussing' => 'Deal on Discussion',
            'Settled'    => 'Deal finished',
            'Dropped'    => 'Deal dropped',
        ];

        // Count per status (iterate mapping so labels are stable)
        $statusCounts = [];
        foreach ($statusMap as $label => $dbValue) {
            $statusCounts[$label] = Inquiry::where('process_level', $dbValue)->count();
        }

        // Inquiries in date range (for numeric totals)
        $rangeInquiriesCount = Inquiry::whereBetween('inquiry_date', [$from->toDateString(), $to->toDateString()])->count();

        // Per-day counts for chart -> group by date
        // We'll build an array of dates from $from -> $to and fill zeros for missing days
        $period = [];
        $cursor = Carbon::parse($from);
        while ($cursor->lte($to)) {
            $period[] = $cursor->toDateString();
            $cursor->addDay();
        }

        // Fetch counts grouped by date (DB may return only present days)
        $rows = Inquiry::selectRaw('DATE(inquiry_date) as date, COUNT(*) as cnt')
            ->whereBetween('inquiry_date', [$from->toDateString(), $to->toDateString()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('cnt', 'date')
            ->toArray();

        // Build series aligned to $period
        $series = [];
        foreach ($period as $d) {
            $series[] = isset($rows[$d]) ? (int) $rows[$d] : 0;
        }

        return response()->json([
            'success' => true,
            'meta' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'total_inquiries' => $totalInquiries,
                'range_inquiries' => $rangeInquiriesCount,
                'total_companies' => $totalCompanies,
                'total_customers' => $totalCustomers,
            ],
            'status_counts' => $statusCounts,
            'labels' => $period,
            'series' => $series,
        ]);
    }
}
