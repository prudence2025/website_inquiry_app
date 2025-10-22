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

        // ✅ Use correct process_level values stored in DB
        $statusMap = [
            'Received'   => 'Received',
            'Quoted'     => 'Quoted',
            'Discussing' => 'Discussing',
            'Settled'    => 'Settled',
            'Dropped'    => 'Dropped',
        ];

        // Correct counts
        $statusCounts = [];
        foreach ($statusMap as $label => $dbValue) {
            $statusCounts[$label] = Inquiry::where('process_level', $dbValue)->count();
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

        // Pass to Blade
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
}
