<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Expense;
use App\Http\Controllers\Controller;

class GetTotalExpanseController extends Controller
{
    
    public function getTotalExpense()
    {
        // 1) Sum of all expenses
        $totalExpense = Expense::sum('amount');
        
        // 2) Sum of expenses from the last week
        $totalExpenseLastWeek = Expense::where('expense_date', '>=', now()->subWeek())
            ->sum('amount');
        
        // 3) Calculate the difference
        $totalExpenseDifference = $totalExpense - $totalExpenseLastWeek;
        
        // 4) Return JSON
        return response()->json([
            'totalExpense'            => $totalExpense,
            'totalExpenseLastWeek'    => $totalExpenseLastWeek,
            'totalExpenseDifference'  => $totalExpenseDifference,
        ]);
    }
    

}
