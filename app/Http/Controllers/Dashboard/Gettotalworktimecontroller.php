<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Purchase;
use App\Http\Controllers\Controller;

class Gettotalworktimecontroller extends Controller
{
    
    public function getWorkTimeSummary()
    {
        // 1) Sum total minutes across all shifts
        $totalMinutes = DB::table('shifts')
            ->sum(DB::raw("TIMESTAMPDIFF(MINUTE, time_open, time_close)"));
    
        // Convert minutes -> hours (rounded to 2 decimals)
        $totalHours = round($totalMinutes / 60, 2); // e.g., 125.50
    
        // 2) Calculate how many "days" that total hours represents
        //    24 hours = 1 day
        $amountDay = round($totalHours / 24, 2);  // e.g., 125.50 / 24 = 5.23 days
    
        // 3) Calculate how many "weeks" that total hours represents
        //    7 days = 1 week
        $amountWeek = round($amountDay / 7, 2);   // e.g., 5.23 / 7 = 0.75 weeks
    
        // Return JSON with the three fields
        return response()->json([
            'total_hour'  => $totalHours,   // e.g., 125.50
            'amount_day'  => $amountDay,    // e.g., 5.23
            'amount_week' => $amountWeek,   // e.g., 0.75
        ]);
    }
    
    
    

}
