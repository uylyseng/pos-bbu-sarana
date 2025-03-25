<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetTotalPurchaseController extends Controller
{
    public function getTotalPurchase()
    {
        // Get the total purchase sum
        $totalPurchase = Purchase::sum('total');
        
        // Get total purchase for last week
        $totalPurchaseLastWeek = Purchase::where('purchase_date', '>=', now()->subWeek())->sum('total');
        
        // Calculate the difference
        $totalPurchaseDifference = $totalPurchase - $totalPurchaseLastWeek;
        
        // Return data as JSON
        return response()->json([
            'totalPurchase' => $totalPurchase,
            'totalPurchaseLastWeek' => $totalPurchaseLastWeek,
            'totalPurchaseDifference' => $totalPurchaseDifference
        ]);
    }
}
