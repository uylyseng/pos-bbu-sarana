<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GetDailySaleController extends Controller
{
    // public function dailySale()
    // {
    //     // 1) Retrieve all daily sales data (no pagination)
    //     $dailySales = DB::table('order_items')
    //         ->join('orders', 'order_items.order_id', '=', 'orders.id')
    //         ->join('payments', 'payments.order_id', '=', 'orders.id')
    //         ->select(
    //             DB::raw('DATE(orders.created_at) AS sale_date'),
    //             DB::raw('SUM(order_items.quantity) AS total_item'),
    //             DB::raw('SUM(order_items.quantity * order_items.unit_price) AS total_sales'),
    //             DB::raw('SUM(orders.subtotal) AS total_subtotal'),
    //             DB::raw('SUM(orders.discount) AS total_discount'),
    //             DB::raw('SUM(orders.total) AS total_amount'),
    //             DB::raw('SUM(payments.amount) AS total_paid'),
    //             DB::raw('SUM(payments.changeusd) AS total_changeusd')
    //         )
    //         ->groupBy(DB::raw('DATE(orders.created_at)'))
    //         ->orderBy('sale_date', 'desc')
    //         ->get(); // No pagination; get all rows

    //     // 2) Return the Blade view with daily sales data
    //     return view('reports.dailysale', [
    //         'dailySales' => $dailySales,
    //     ]);
    // }
}
