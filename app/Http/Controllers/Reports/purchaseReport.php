<?php

namespace App\Http\Controllers\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseReport extends Controller
{
    /**
     * Generate the purchase report with purchase items.
     */
    public function purchaseReport(Request $request)
{
    // Parse the start and end dates from the request
    $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
    $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));

    // Get total purchases within the date range
    $totalPurchase = Purchase::whereBetween('purchase_date', [$startDate, $endDate])->sum('total');

    // Get purchases from the previous week
    $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
    $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
    $lastWeekPurchase = Purchase::whereBetween('purchase_date', [$lastWeekStart, $lastWeekEnd])->sum('total');

    // Calculate the difference
    $purchaseDifference = $totalPurchase - $lastWeekPurchase;

    // Fetch suppliers
    $suppliers = Supplier::all();

    // Get records per page from the query, default to 10 if not set
    $perPage = $request->get('perPage', 10);

    // Validate the 'perPage' parameter to allow only specific values (2, 5, 10, 20)
    $perPage = in_array($perPage, [2, 5, 10, 20]) ? $perPage : 10; 

    // Fetch the filtered purchases with pagination
    $purchases = Purchase::with('purchaseItems.product', 'supplier', 'paymentMethod')
        ->whereBetween('purchase_date', [$startDate, $endDate])
        ->when($request->input('supplier_id'), function ($query) use ($request) {
            return $query->where('supplier_id', $request->input('supplier_id'));
        })
        ->orderBy('purchase_date', 'desc')
        ->paginate($perPage); // Use dynamic records per page

    // Calculate start and end records for pagination
    $startRecord = ($purchases->currentPage() - 1) * $purchases->perPage() + 1;
    $endRecord = min($purchases->currentPage() * $purchases->perPage(), $purchases->total());

    // Return the report view with necessary data
    return view('reports.purchases', compact(
        'purchases', 
        'suppliers', 
        'totalPurchase', 
        'purchaseDifference', 
        'lastWeekPurchase', 
        'startDate', 
        'endDate', 
        'startRecord', 
        'endRecord'
    ));
}

    

    public function purchasePreview($purchaseId)
    {
        // Fetch the purchase and its related items, payment method, and supplier
        $purchase = Purchase::with('purchaseItems.product', 'supplier', 'paymentMethod')
            ->findOrFail($purchaseId); // Fetch the purchase by its ID

        // Return the view with purchase details
        return view('reports.purchasepreview', compact('purchase'));
    }
    public function printPurchasepreview($purchaseId)
{
    // Retrieve the purchase with its related items, supplier, and payment method.
    $purchase = Purchase::with('purchaseItems.product', 'supplier', 'paymentMethod')
        ->findOrFail($purchaseId);

    // Return a print-friendly view (e.g., resources/views/reports/purchaseprint.blade.php)
    return view('reports.purchasepreviewprint', compact('purchase'));
}


    public function printPurchase($purchaseId)
    {
        // 1) Retrieve the purchase, along with any related data needed.
        $purchase = Purchase::with('purchaseItems.product', 'supplier', 'paymentMethod')
            ->findOrFail($purchaseId);

        // 2) Return a Blade view that is styled for printing (no sidebars, minimal styling, etc.).
        //    You might create a new Blade file named 'purchaseprint.blade.php' in resources/views/reports/.
        return view('reports.purchaseprint', compact('purchase'));
    }
    public function printPurchases(Request $request)
{
    // Parse the start and end dates (reuse your logic from purchaseReport)
    $startDate = \Carbon\Carbon::parse($request->input('start_date', \Carbon\Carbon::now()->startOfMonth()));
    $endDate   = \Carbon\Carbon::parse($request->input('end_date', \Carbon\Carbon::now()->endOfMonth()));

    // Optionally filter by supplier if you want
    $query = \App\Models\Purchase::whereBetween('purchase_date', [$startDate, $endDate]);

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->input('supplier_id'));
    }

    // Fetch the data
    $purchases = $query->orderBy('purchase_date', 'desc')->get();

    // Return a "print-friendly" view
    return view('reports.purchases_print', [
        'purchases' => $purchases,
        'startDate' => $startDate,
        'endDate'   => $endDate,
    ]);
}

}
