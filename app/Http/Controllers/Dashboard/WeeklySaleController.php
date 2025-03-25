<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class WeeklySaleController extends Controller
{
    public function getMondayToSundaySale()
    {
        // 1) Determine the start and end of the current week
        // By default, Carbon’s startOfWeek() uses Monday as the first day
        // endOfWeek() uses Sunday as the last day
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // 2) Query orders between Monday 00:00 and Sunday 23:59 of the current week
        // Group by the day name (e.g. Monday, Tuesday, etc.) and sum the 'total' column
        $results = DB::table('orders')
            ->select(
                DB::raw("DAYNAME(created_at) as day_name"),
                DB::raw("SUM(total) as sale")
            )
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('day_name')
            ->get()
            ->keyBy('day_name');

        // 3) Prepare arrays for labels (Mon-Sun) and sales
        $daysOfWeek = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $labels = [];
        $sales  = [];

        foreach ($daysOfWeek as $day) {
            $labels[] = $day;
            // If we have data for this day, use it; otherwise default to 0
            $sales[] = isset($results[$day]) ? (float) $results[$day]->sale : 0;
        }

        // 4) Return the data as JSON for your front-end chart
        return response()->json([
            'labels' => $labels,
            'sales'  => $sales,
        ]);
    }
}
