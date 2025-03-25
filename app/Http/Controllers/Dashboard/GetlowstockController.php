<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Http\Controllers\Controller;

class GetlowstockController extends Controller
{
    
    public function getLowStockCount()
{
    $count = Product::where('is_stock', 'have_stock')
        ->where('active', 1)
        ->whereColumn('qty', '<=', 'low_stock')
        ->count();

    return response()->json([
        'low_stock_count' => $count,
    ]);
}

    
    

}
