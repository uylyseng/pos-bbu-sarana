<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Displays the main Sales Report with date filters.
     */
    public function sales(Request $request)
    {
        try {
            // 1) Date range defaults
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
    
            // 2) Per-page logic (allowed: 2,4,5,10,20)
            $perPage = $request->input('per_page', 10);
            $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
            // 3) Build the base query
            $salesQuery = DB::table('orders as o')
                ->leftJoin('tables as t', 'o.table_id', '=', 't.id')
                ->leftJoin('payments as pay', 'pay.order_id', '=', 'o.id')
                ->selectRaw("
                    DATE(o.created_at) AS sale_date,
                    t.name AS table_name,
                    o.discount AS total_discount,
                    pay.status AS payment_status,
                    o.status AS order_status,
                    pay.amount AS amount,
                    pay.changeUSD AS changeusd,
                    o.total AS order_total
                ")
                ->whereBetween('o.created_at', [$startDate, $endDate])
                ->orderBy('o.created_at', 'asc');
    
            // 4) Optional filters
            //    a) Filter by table_id (if provided)
            if ($request->filled('table_id')) {
                $salesQuery->where('o.table_id', $request->input('table_id'));
            }
            //    b) Filter by order status
            if ($request->filled('order_status')) {
                $salesQuery->where('o.status', $request->input('order_status'));
            }
            //    c) Filter by payment status
            if ($request->filled('payment_status')) {
                $salesQuery->where('pay.status', $request->input('payment_status'));
            }
    
            // 5) Paginate the results (returns a LengthAwarePaginator)
            $salesPaginated = $salesQuery->paginate($perPage);
    
            // 6) Group only the *current page's* items by 'sale_date'
            //    => This means each page can have multiple date groups or partial dates if a date has more than $perPage items
            $groupedSales = $salesPaginated->getCollection()->groupBy('sale_date');
    
            // 7) Return the Blade view with grouped data + the paginator
            return view('reports.sales', [
                'sales'          => $groupedSales,       // Grouped items for this page
                'salesPaginated' => $salesPaginated,     // Paginator for next/prev links
                'startDate'      => Carbon::parse($startDate),
                'endDate'        => Carbon::parse($endDate),
                // pass filter values back to view to keep form state
                'tableId'        => $request->input('table_id'),
                'orderStatus'    => $request->input('order_status'),
                'paymentStatus'  => $request->input('payment_status'),
                'perPage'        => $perPage,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sales data: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }
    

    /**
     * Displays the "Print" version of the Sales Report.
     */
    public function printSale(Request $request)
    {
        try {
            // 1) Date range
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
    
            // 2) Build query (same logic as 'sales')
            $salesQuery = DB::table('orders as o')
                ->leftJoin('tables as t', 'o.table_id', '=', 't.id')
                ->leftJoin('payments as pay', 'pay.order_id', '=', 'o.id')
                ->selectRaw("
                    DATE(o.created_at) AS sale_date,
                    t.name AS table_name,
                    o.discount AS total_discount,
                    pay.status AS payment_status,
                    o.status AS order_status,
                    pay.amount AS amount,
                    pay.changeUSD AS changeusd,
                    o.total AS order_total
                ")
                ->whereBetween('o.created_at', [$startDate, $endDate])
                ->orderBy('o.created_at', 'asc');
    
            // 3) Execute the query and retrieve the data
            $sales = $salesQuery->get();
    
            // 4) Return the 'reports.print_sales' view with the data
            return view('reports.print_sales', [
                'sales'     => $sales,
                'startDate' => Carbon::parse($startDate),
                'endDate'   => Carbon::parse($endDate),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching print sales data: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }
    
}
