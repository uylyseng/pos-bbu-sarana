<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MostsellproductController extends Controller
{
    public function getMostSoldProducts()
    {
        // 1) Per-product totals (quantity + total price), no search or pagination
        $topSellingProducts = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->select(
                'p.name_en as product',
                DB::raw('SUM(oi.quantity) as quantity'),
                DB::raw('SUM(oi.quantity * oi.unit_price) as total_price')
            )
            ->groupBy('p.name_en')
            ->orderByDesc('quantity') // or orderByDesc('total_price')
            ->get();
    
        // 2) Grand totals (no date or search filter)
        $grandTotals = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->select(
                DB::raw('SUM(oi.quantity) as quantity'),
                DB::raw('SUM(oi.quantity * oi.unit_price) as total_price')
            )
            ->first();
    
        // 3) Return JSON with no search/pagination fields
        return response()->json([
            'data'               => $topSellingProducts,      // array of { product, quantity, total_price }
            'grandTotalQuantity' => $grandTotals->quantity ?? 0,
            'grandTotalPrice'    => $grandTotals->total_price ?? 0,
        ]);
    }
    
    
    
    

}
