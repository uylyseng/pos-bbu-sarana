<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProfitlossController extends Controller
{
    public function getProfitLoss()
    {
        // 1) Sum of income (e.g., "payments" table)
        $totalIncome = DB::table('payments')->sum('amount');

        // 2) Sum of expenses (e.g., "expenses" table)
        $totalExpenses = DB::table('expenses')->sum('amount');

        // 3) Net difference
        $net = $totalIncome - $totalExpenses;

        // 4) Determine profit/loss/break-even
        if ($net > 0) {
            $status = 'Profit';
        } elseif ($net < 0) {
            $status = 'Loss';
        } else {
            $status = 'Break-even';
        }

        // 5) Return as JSON or pass to a view
        return response()->json([
            'totalIncome'   => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'net'           => $net,
            'status'        => $status,
        ]);
    }
}
