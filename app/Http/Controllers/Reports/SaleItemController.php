<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SaleItemController extends Controller
{
    public function saleitems(Request $request)
    {
        try {
            // 1) Parse optional date range from request
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

            // 2) Get per-page value with validation
            $perPage = $request->input('per_page', 10);
            $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;

            // 3) Build query with error handling
            $saleItemsQuery = DB::table('order_items as oi')
                ->join('orders as o', 'oi.order_id', '=', 'o.id')
                ->join('products as p', 'oi.product_id', '=', 'p.id')
                ->select([
                    DB::raw("DATE(o.created_at) AS sale_date"),
                    'p.name_en AS product_name',
                    'oi.quantity AS qty',
                    'o.discount AS item_discount',
                    'oi.unit_price AS base_price',
                    DB::raw("(oi.quantity * oi.unit_price) AS sale_price")
                ])
                ->whereBetween('o.created_at', [$startDate, $endDate]);

            // 4) Execute the query with pagination
            $saleItems = $saleItemsQuery->paginate($perPage);

            // 5) Return the Blade view
            return view('reports.sale-items', [
                'saleItems' => $saleItems,
                'startDate' => Carbon::parse($startDate),
                'endDate'   => Carbon::parse($endDate),
                'perPage'   => $perPage, // Send perPage to the view for UI updates
            ]);
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error("Error in SaleItemController: " . $e->getMessage());
            return response()->view('errors.500', ['message' => 'Internal Server Error'], 500);
        }
    }
    public function printSaleItems(Request $request)
    {
        try {
            // 1) Parse date range from request
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
    
            // 2) Query sale items within the selected date range
            $saleItems = DB::table('order_items as oi')
                ->join('orders as o', 'oi.order_id', '=', 'o.id')
                ->join('products as p', 'oi.product_id', '=', 'p.id')
                ->select([
                    DB::raw("DATE(o.created_at) AS sale_date"),
                    'p.name_en AS product_name',
                    'oi.quantity AS qty',
                    'o.discount AS item_discount',
                    'oi.unit_price AS base_price',
                    DB::raw("(oi.quantity * oi.unit_price) AS sale_price")
                ])
                ->whereBetween('o.created_at', [$startDate, $endDate])
                ->get();
    
            // 3) Return print view
            return view('reports.print-sale-items', [
                'saleItems' => $saleItems,
                'startDate' => Carbon::parse($startDate),
                'endDate'   => Carbon::parse($endDate),
            ]);
        } catch (\Exception $e) {
            Log::error("Error in printSaleItems: " . $e->getMessage());
            return response()->view('errors.500', ['message' => 'Internal Server Error'], 500);
        }
    }
    

}
