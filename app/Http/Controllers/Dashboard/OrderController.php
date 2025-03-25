<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function getRecentOrders()
    {
        // Run the query to get the 5 most recent orders
        $recentOrders = DB::table('orders as o')
            ->join('order_items as oi', 'o.id', '=', 'oi.order_id')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->select(
                'c.name as customers',
                'p.name_en as product',
                'oi.quantity',
                'oi.unit_price as price',
                'o.status',
                'o.created_at'
            )
            ->orderBy('o.created_at', 'desc')
            ->limit(5)
            ->get();

        // Return the data as JSON
        return response()->json($recentOrders);
    }
}
