<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DailySaleController extends Controller
{
    /**
     * Generate the daily sales report with pagination.
     */
    public function dailySalesReport(Request $request)
    {
        // Optionally, filter by date range
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));
    
        // Get the search query if it exists
        $searchQuery = $request->input('search', '');  // Default is an empty string
    
        // Get per-page value with validation
        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Get the daily sales data based on the date range with pagination
        $dailySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw('DATE(orders.created_at) as sale_date'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales'),
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('orders.id', 'like', '%' . $searchQuery . '%');
            }) // Apply search filter if query exists
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->orderBy('sale_date', 'desc')  // Order by the sale date (latest first)
            ->paginate($perPage); // Use dynamic per-page value
    
        // Get the start and end record numbers for the current page
        $startRecord = ($dailySales->currentPage() - 1) * $dailySales->perPage() + 1;
        $endRecord = min($dailySales->currentPage() * $dailySales->perPage(), $dailySales->total());
    
        // Return the report view with daily sales data, pagination, date range, and search query
        return view('reports.daily-sales', compact('dailySales', 'startDate', 'endDate', 'searchQuery', 'startRecord', 'endRecord', 'perPage'));
    }
    public function printDailySalesReport(Request $request)
{
    // 1. Get filters
    $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
    $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));
    $searchQuery = $request->input('search', '');

    // 2. Query the full daily sales data (no pagination)
    $dailySales = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->select(
            DB::raw('DATE(orders.created_at) as sale_date'),
            DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales'),
            DB::raw('SUM(order_items.quantity) as total_quantity')
        )
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->when($searchQuery, function ($query, $searchQuery) {
            return $query->where('orders.id', 'like', '%' . $searchQuery . '%');
        })
        ->groupBy(DB::raw('DATE(orders.created_at)'))
        ->orderBy('sale_date', 'desc')
        ->get();

    return view('reports.print_dailysale', compact('dailySales', 'startDate', 'endDate'));
}

    
}
