<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;


use Illuminate\Support\Facades\DB;

class ShiftsController extends Controller
{
    public function shiftReport(Request $request)
    {
        // 1) Retrieve 'per_page' from the request, defaulting to 10
        $perPage = $request->input('per_page', 10);
        
        // Validate allowed values for 'per_page'
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;

        // 2) Build a query for shifts
        $query = Shift::select('id', 'user_id', 'time_open', 'time_close', 'cash_in_hand', 'total_cash');

        // 3) If a user_id is provided (not empty), filter by that user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // 4) Filter by start_date if provided
        if ($request->filled('start_date')) {
            $query->whereDate('time_open', '>=', $request->input('start_date'));
        }

        // 5) Filter by end_date if provided
        if ($request->filled('end_date')) {
            $query->whereDate('time_close', '<=', $request->input('end_date'));
        }

        // 6) Paginate the results, preserving 'per_page' and 'user_id' in the query string
        $shifts = $query->paginate($perPage)->appends([
            'per_page' => $perPage,
            'user_id'  => $request->input('user_id'),
        ]);

        // 7) Retrieve all users to populate the dropdown in the view
        $users = User::select('id', 'name')->get();

        // 8) Return the view with shifts, perPage, and users
        return view('reports.shifts', compact('shifts', 'perPage', 'users'));
    }

    public function printShiftReport($shiftId, $userId)
    {
        // 1) Fetch the shift details
        $shift = Shift::find($shiftId);
    
        // 2) Handle the case when the shift is not found
        if (!$shift) {
            return redirect()->back()->with('error', 'Shift not found.');
        }
    
        // 3) Fetch orders created by the user and within the shift's time range
        $orders = Order::where('created_by', $userId)
            ->whereBetween('created_at', [$shift->time_open, $shift->time_close])
            ->get();
    
        // 4) Fetch order items with associated products
        $orderItems = OrderItem::with('product')
            ->whereIn('order_id', $orders->pluck('id'))
            ->get();
    
        // 5) Fetch payments for these orders
        $payments = Payment::whereIn('order_id', $orders->pluck('id'))->get();
    
        // 6) (Optional) If you want aggregated amounts:
        $totalAmount   = $payments->sum('amount');
        $totalChangeUSD = $payments->sum('changeUSD');
    
        // 7) Return the view with shift details, orders, order items, and payments
        return view('reports.print_shifts', [
            'shift'         => $shift,
            'orders'        => $orders,
            'orderItems'    => $orderItems,
            'payments'      => $payments,
            'totalAmount'   => $totalAmount,
            'totalChangeUSD'=> $totalChangeUSD,
        ]);
    }
    
}
