<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlySale extends Controller
{
    /**
     * Generate the monthly sales report with pagination.
     */
    public function monthlySalesReport(Request $request)
    {
        // Get selected month (default: current month)
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        // Validate the month format (YYYY-MM)
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selectedMonth)) {
            $selectedMonth = Carbon::now()->format('Y-m'); // Default to current month if invalid
        }

        // Get search query if it exists
        $searchQuery = $request->input('search', '');  

        // Get per-page value with validation
        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;

        // Get the monthly sales data based on the selected month
        $monthlySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as sale_month"), 
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales'),
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->whereRaw("DATE_FORMAT(orders.created_at, '%Y-%m') = ?", [$selectedMonth]) // Filter by month
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('orders.id', 'like', '%' . $searchQuery . '%');
            })
            ->groupBy(DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m')"))
            ->orderBy('sale_month', 'desc')
            ->paginate($perPage);

        // Get the start and end record numbers for the current page
        $startRecord = ($monthlySales->currentPage() - 1) * $monthlySales->perPage() + 1;
        $endRecord = min($monthlySales->currentPage() * $monthlySales->perPage(), $monthlySales->total());

        // Return the report view with monthly sales data, pagination, and selected month
        return view('reports.monthly-sales', compact('monthlySales', 'selectedMonth', 'searchQuery', 'startRecord', 'endRecord', 'perPage'));
    }
    public function printMonthlySalesReport(Request $request)
{
    // Get selected month (default: current month)
    $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

    // Validate format (YYYY-MM)
    if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selectedMonth)) {
        $selectedMonth = Carbon::now()->format('Y-m');
    }

    // Query data for selected month
    $sales = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->select(
            DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as sale_month"),
            DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales'),
            DB::raw('SUM(order_items.quantity) as total_quantity')
        )
        ->whereRaw("DATE_FORMAT(orders.created_at, '%Y-%m') = ?", [$selectedMonth])
        ->groupBy(DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m')"))
        ->orderBy('sale_month', 'desc')
        ->get();

    // Pass to view
    return view('reports.print_monthlysale', [
        'sales' => $sales,
        'selectedMonth' => Carbon::createFromFormat('Y-m', $selectedMonth),
    ]);
}

}
