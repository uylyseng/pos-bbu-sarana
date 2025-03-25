<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Purchase;
use App\Http\Controllers\Controller;

class GetSumaryController extends Controller
{
    
    public function getSummary()
    {
        // 1) Income: sum of "amount" in a "paymemt" table
        $income = DB::table('payments')->sum('amount');
    
        // 2) Expenses: sum of "amount" in an "expenses" table
        $expenses = DB::table('expenses')->sum('amount');
    
        // 3) Profit: basic calculation
        $profit = $income - $expenses;
    
        // 4) Purchases: sum of "total" in a "purchases" table
        $purchase = DB::table('purchases')->sum('total');
    
        // 5) Return JSON with the four values
        return response()->json([
            'income'    => $income,
            'profit'    => $profit,
            'expenses'  => $expenses,
            'purchase'  => $purchase,
        ]);
    }
    
    

}
