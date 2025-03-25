<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use Carbon\Carbon; // Import Carbon to handle date and time operations

use App\Http\Controllers\Controller; // Make sure to import the base Controller class

class CashierDashboardController extends Controller // Extend the base Controller, not Dashboard
{
    public function index()
    {
        // Sum all orders placed "today" (i.e., from midnight to now).
        // Adjust 'total' to match your Order model's column name (e.g., total_price, subtotal, etc.).
        $totalSalesToday = Order::whereDate('created_at', Carbon::today())
            ->sum('total'); // Assuming the column in the Order model is 'total'

        // Pass the 'totalSalesToday' variable to the view
        return view('dashboard.cashier', compact('totalSalesToday'));
    }
}
