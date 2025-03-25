<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
class MostsaleproducttodayController extends Controller
{
    public function getMostSoldProductToday()
    {
        $mostSoldProductToday = OrderItem::join('products as p', 'order_items.product_id', '=', 'p.id')
            ->select('p.name_en as product_name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            // Only include orders created today
            ->whereDate('order_items.created_at', Carbon::today())
            ->groupBy('order_items.product_id', 'p.name_en')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->first();
    
        if ($mostSoldProductToday) {
            // Assign a field for clarity
            $mostSoldProductToday->today_sold = $mostSoldProductToday->total_quantity;
        }
    
        return $mostSoldProductToday;
    }
}
