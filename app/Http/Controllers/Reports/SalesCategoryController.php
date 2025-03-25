<?php

namespace App\Http\Controllers\Reports;

use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SalesCategoryController extends Controller
{
    /**
     * Generate the sales by category report.
     */
    public function salesCategoryReport(Request $request)
    {
        // Get the 'perPage' parameter from the query string, defaulting to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Optionally, filter by date range
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));
    
        // Get sales data grouped by product categories with pagination
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id as category_id',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales')
            )
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales') // Order by total sales in descending order
            ->paginate($perPage); // Paginate with dynamic per-page selection
    
        // Calculate start and end record numbers for the current page
        $startRecord = ($salesByCategory->currentPage() - 1) * $salesByCategory->perPage() + 1;
        $endRecord = min($salesByCategory->currentPage() * $salesByCategory->perPage(), $salesByCategory->total());
    
        // Return the view with sales by category data
        return view('reports.sales-by-category', compact('salesByCategory', 'startDate', 'endDate', 'startRecord', 'endRecord', 'perPage'));
    }
    public function printSalesByCategory(Request $request)
{
    // Get date range or use default (current month)
    $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
    $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));

    // Get sales data grouped by product categories
    $salesByCategory = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select(
            'categories.id as category_id',
            'categories.name as category_name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales')
        )
        ->whereBetween('order_items.created_at', [$startDate, $endDate])
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_sales')
        ->get(); // Fetch all without pagination for printing

    // Return the print view
    return view('reports.print_salebycategory', compact('salesByCategory', 'startDate', 'endDate'));
}

    
}
