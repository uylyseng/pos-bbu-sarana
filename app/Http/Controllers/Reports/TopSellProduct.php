<?php

namespace App\Http\Controllers\Reports;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TopSellProduct extends Controller
{
    /**
     * Generate the most sold products report based on order items
     */
    public function topSellingProducts(Request $request)
    {
        // 1) Date Range
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
        $endDate   = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));

        // 2) Optional search query
        $searchQuery = $request->input('search', '');  // Default is empty

        // 3) Validate perPage (only 2, 4, 5, 10, 20 allowed)
        $perPage = $request->get('perPage', 10);
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;

        // 4) Query the order_items table for most sold products
        $topSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name_en',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales')
            )
            ->whereBetween('order_items.created_at', [$startDate, $endDate]) // Filter by date range
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('products.name_en', 'like', '%' . $searchQuery . '%');
            })
            ->groupBy('products.id', 'products.name_en')
            ->orderByDesc('total_sales') // Sort by highest total sales first
            ->paginate($perPage);        // Paginate using the validated perPage

        // 5) Calculate the starting and ending record numbers for the current page
        $startRecord = ($topSellingProducts->currentPage() - 1) * $topSellingProducts->perPage() + 1;
        $endRecord   = min($topSellingProducts->currentPage() * $topSellingProducts->perPage(), $topSellingProducts->total());

        // 6) Return the Blade view, passing the paginated results and extra info
        return view('reports.topsellingproduct', [
            'topSellingProducts' => $topSellingProducts,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'searchQuery'       => $searchQuery,
            'startRecord'       => $startRecord,
            'endRecord'         => $endRecord,
            'perPage'           => $perPage,
        ]);
    }
    public function printTopSellingProducts(Request $request)
{
    // 1) Date range
    $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
    $endDate   = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));

    // 2) Optional search query
    $searchQuery = $request->input('search', '');

    // 3) Query top selling products (no pagination for print)
    $topSellingProducts = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->select(
            'products.id',
            'products.name_en',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales')
        )
        ->whereBetween('order_items.created_at', [$startDate, $endDate])
        ->when($searchQuery, function ($query, $searchQuery) {
            return $query->where('products.name_en', 'like', '%' . $searchQuery . '%');
        })
        ->groupBy('products.id', 'products.name_en')
        ->orderByDesc('total_sales')
        ->get();

    // 4) Pass to view
    return view('reports.print_topselling', compact(
        'topSellingProducts',
        'startDate',
        'endDate',
        'searchQuery'
    ));
}

}
