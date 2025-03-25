<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalesByCategoryController extends Controller
{
    public function getSalesByCategory()
    {
        // 1) Category totals
        $categoryTotals = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select('c.name as category', DB::raw('SUM(oi.subtotal) as total'))
            ->groupBy('c.name')
            ->orderByDesc('total')
            ->get();
    
        // 2) Grand total
        $grandTotal = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(DB::raw('SUM(oi.subtotal) as total'))
            ->first();
    
        // Combine them in an array
        return response()->json([
            'categories' => $categoryTotals,         // array of per-category rows
            'grandTotal' => $grandTotal->total ?? 0  // single value
        ]);
    }
    
}
