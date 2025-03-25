<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class MonthlySaleController extends Controller
{
    public function getMonthlySale()
    {
        // Get sales data grouped by month (using the numeric month)
        $results = DB::table('orders')
            ->select(DB::raw("MONTH(created_at) as month_num"), DB::raw("SUM(total) as sale"))
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month_num')
            ->get()
            ->keyBy('month_num'); // key the results by month number for easier access

        // Prepare arrays for labels and sales for all 12 months
        $labels = [];
        $sales = [];

        // Loop through months 1 to 12
        for ($m = 1; $m <= 12; $m++) {
            // Get abbreviated month name (e.g., Jan, Feb, etc.)
            $labels[] = date('M', mktime(0, 0, 0, $m, 1));
            // If data exists for the month, use its sale value; otherwise, use 0
            $sales[] = isset($results[$m]) ? (float) $results[$m]->sale : 0;
        }

        // Return the data as JSON
        return response()->json([
            'labels' => $labels,
            'sales'  => $sales,
        ]);
    }
}
