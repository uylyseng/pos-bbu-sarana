<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Purchase;
use App\Http\Controllers\Controller;

class DailySalesController extends Controller
{
    public function getDailySales(Request $request)
    {
        $startDate = $request->input('start_date', '2025-02-01');
        $endDate = Carbon::today()->toDateString();
    
        // Query to get total sum, total discount, and count grouped by date
        $results = DB::table('orders')
            ->select(
                DB::raw('DATE(order_date) as order_day'),
                DB::raw('SUM(total) as total_sum'),
                DB::raw('SUM(discount) as total_discount'),
                DB::raw('COUNT(*) as order_count')
            )
            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->get();

        $events = [];
        foreach ($results as $result) {
            $events[] = [
                'title' => 'Total: $' . ($result->total_sum ?? 0),
                'start' => $result->order_day,
                'extendedProps' => [
                    'discount' => $result->total_discount ?? 0,
                    'count'    => $result->order_count ?? 0
                ],
                'display' => 'block'
            ];
        }

        // Return JSON if request is an API call
        if ($request->wantsJson()) {
            return response()->json(['events' => $events]);
        }

        // Return view with data
        return view('reports.daily-sales', ['events' => $events]);
    }
  

}
