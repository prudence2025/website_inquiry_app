<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    // Default range: last 30 days
    $to = $request->filled('to')
        ? Carbon::parse($request->input('to'))->endOfDay()
        : Carbon::now()->endOfDay();

    $from = $request->filled('from')
        ? Carbon::parse($request->input('from'))->startOfDay()
        : (clone $to)->subDays(29)->startOfDay();

    // Meta totals
    $totalInquiries = Inquiry::count();
    $totalCompanies = Company::count();
    $totalCustomers = Customer::count();

    // ✅ Total earnings (all Settled inquiries)
    $totalEarnings = Inquiry::where('process_level', 'Settled')->sum('amount');

    // ✅ Earnings in selected date range (only Settled within range)
    $rangeEarnings = Inquiry::where('process_level', 'Settled')
        ->whereBetween('inquiry_date', [$from->toDateString(), $to->toDateString()])
        ->sum('amount');

    // ✅ Status counts
    $statusMap = [
        'Received'   => 'Received',
        'Quoted'     => 'Quoted',
        'Discussing' => 'Discussing',
        'Settled'    => 'Settled',
        'Dropped'    => 'Dropped',
    ];

    $statusCounts = [];
    foreach ($statusMap as $label => $dbValue) {
        $statusCounts[$label] = Inquiry::where('process_level', $dbValue)->count();
    }

    // ✅ Inquiries in date range
    $rangeInquiries = Inquiry::whereBetween('inquiry_date', [$from->toDateString(), $to->toDateString()])->count();

    // ✅ Chart dataset
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

    // ✅ Pass data to view
    return view('dashboard', [
        'totalInquiries' => $totalInquiries,
        'totalCompanies' => $totalCompanies,
        'totalCustomers' => $totalCustomers,
        'totalEarnings'  => $totalEarnings,
        'rangeEarnings'  => $rangeEarnings,
        'rangeInquiries' => $rangeInquiries,
        'statusCounts'   => $statusCounts,
        'labels'         => $period,
        'series'         => $series,
        'from'           => $from->toDateString(),
        'to'             => $to->toDateString(),
    ]);
}

}
