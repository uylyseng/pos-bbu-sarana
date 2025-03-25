<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the purchases.
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the request, default to 10 if not provided
        $perPage = $request->get('perPage', 10);
        
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Fetch the paginated purchases based on the validated 'perPage' value
        $purchases = Purchase::paginate($perPage);
        
        // Return the view with the purchases and the selected 'perPage' value
        return view('purchases.index', compact('purchases', 'perPage'));
    }
    
    
    /**
     * Search for products (only those with 'have_stock').
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        if (!$query) {
            return response()->json(['error' => 'No query provided.'], 400);
        }
    
        Log::info("Search query: " . $query);
    
        // Modify query to only return products where is_stock = 'have_stock'
        $products = Product::where('is_stock', 'have_stock')  // Filter by is_stock = 'have_stock'
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('barcode', $query)
                    ->orWhere('name_en', 'like', '%' . $query . '%')
                    ->orWhere('name_kh', 'like', '%' . $query . '%');
            })
            ->get();
    
        if ($products->isNotEmpty()) {
            Log::info("Products found: " . $products->toJson());
            return response()->json($products);
        }
    
        return response()->json(['error' => 'Product not found.'], 404);
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();
        $units = Unit::all();
    
        return view('purchases.create', compact('suppliers', 'paymentMethods', 'products', 'units'));
    }

    /**
     * Store a newly created purchase in the database.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'purchase_date' => 'required|date',
            'reference'     => 'nullable|string',
            'supplier_id'   => 'required|exists:suppliers,id',
            'status'        => 'nullable|string',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            // Make discount optional or remove it entirely if not needed:
            // 'discount'    => 'nullable|numeric|min:0', 
            'items'         => 'required|array',
            'items.*.product_id'      => 'required|exists:products,id',
            'items.*.quantity'        => 'required|numeric|min:1',
            'items.*.unit_price'      => 'required|numeric|min:0',
            'items.*.purchase_unit_id' => 'required|exists:units,id',
            'items.*.discount'        => 'nullable|numeric|min:0', // If each item can have a discount
        ]);
    
        // Calculate the subtotal and sum of all item discounts
        $subtotal      = 0;
        $totalDiscount = 0;
    
        foreach ($request->items as $item) {
            $lineSubtotal = $item['quantity'] * $item['unit_price'];
            $subtotal    += $lineSubtotal;
            // Add each item’s discount to the total discount
            $totalDiscount += isset($item['discount']) ? $item['discount'] : 0;
        }
    
        // Calculate the final total (subtotal minus total discount)
        $total = $subtotal - $totalDiscount;
    
        // Create purchase record
        $purchase = Purchase::create([
            'purchase_date'     => $request->purchase_date,
            'reference'         => $request->reference ?: 'AUTO-' . time(),
            'supplier_id'       => $request->supplier_id,
            'status'            => $request->status,
            'payment_method_id' => $request->payment_method_id,
            'details'           => $request->details,
            'subtotal'          => $subtotal,
            // Store the sum of all item discounts in the purchase
            'discount'          => $totalDiscount,
            'total'             => $total,
            'created_by'        => Auth::id(),
        ]);
    
        // Create purchase items, then update product quantity if status is "Received"
        foreach ($request->items as $item) {
            $purchaseItem = $purchase->purchaseItems()->create([
                'product_id'       => $item['product_id'],
                'quantity'         => $item['quantity'],
                'unit_price'       => $item['unit_price'],
                'purchase_unit_id' => $item['purchase_unit_id'],
                // Store each item’s discount
                'discount'         => isset($item['discount']) ? $item['discount'] : 0,
            ]);
    
            // If status is "Received", update product stock
            if ($purchase->status === 'Received') {
                $product = Product::find($item['product_id']);
                if ($product && $product->is_stock === 'have_stock') {
                    $purchaseUnit = Unit::find($item['purchase_unit_id']);
                    if ($purchaseUnit) {
                        $conversionRate = $purchaseUnit->conversion_rate ?? 1;
                        $product->qty  += $item['quantity'] * $conversionRate;
                        $product->save();
                    }
                }
            }
        }
    
        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase added successfully.');
    }
    


    
    
    
    
    /**
     * Show the specified purchase.
     */
    public function show($purchaseId)
    {
        $purchase = Purchase::with('purchaseItems')->find($purchaseId);
        return view('purchases.show', compact('purchase'));
    }
    
    /**
     * Show the form for editing the specified purchase.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();
        $units = Unit::all();
    
        return view('purchases.edit', compact('purchase', 'suppliers', 'paymentMethods', 'products', 'units'));
    }

    /**
     * Update the specified purchase and its items.
     */
    public function update(Request $request, $purchaseId)
{
    // Validation
    $request->validate([
        'purchase_date'      => 'required|date',
        'reference'          => 'nullable|string',
        'supplier_id'        => 'required|exists:suppliers,id',
        'status'             => 'nullable|string',
        'payment_method_id'  => 'nullable|exists:payment_methods,id',
        'items'              => 'required|array',
        'items.*.product_id'       => 'required|exists:products,id',
        'items.*.quantity'         => 'required|numeric|min:1',
        'items.*.unit_price'       => 'required|numeric|min:0',
        'items.*.purchase_unit_id' => 'required|exists:units,id',
        'items.*.discount'         => 'nullable|numeric|min:0',
    ]);

    // Calculate subtotal and total discount from all items
    $subtotal      = 0;
    $totalDiscount = 0;

    foreach ($request->items as $item) {
        $lineSubtotal = $item['quantity'] * $item['unit_price'];
        $subtotal    += $lineSubtotal;
        // Accumulate item-level discount
        $totalDiscount += isset($item['discount']) ? $item['discount'] : 0;
    }

    // Compute the final total
    $total = $subtotal - $totalDiscount;

    // Fetch the existing purchase record
    $purchase   = Purchase::findOrFail($purchaseId);
    $oldStatus  = $purchase->status;

    // Update the purchase
    $purchase->update([
        'purchase_date'     => $request->purchase_date,
        'reference'         => $request->reference ?: 'AUTO-' . time(),
        'supplier_id'       => $request->supplier_id,
        'status'            => $request->status,
        'payment_method_id' => $request->payment_method_id,
        'details'           => $request->details,
        'subtotal'          => $subtotal,
        'discount'          => $totalDiscount, // store sum of item discounts
        'total'             => $total,
        'updated_by'        => Auth::id(),
    ]);

    // Remove existing purchase items (so we can re-insert)
    $purchase->purchaseItems()->delete();

    // Re-insert purchase items, then adjust stock if necessary
    foreach ($request->items as $item) {
        $purchaseItem = $purchase->purchaseItems()->create([
            'product_id'       => $item['product_id'],
            'quantity'         => $item['quantity'],
            'unit_price'       => $item['unit_price'],
            'purchase_unit_id' => $item['purchase_unit_id'],
            'discount'         => isset($item['discount']) ? $item['discount'] : 0,
        ]);

        // Update product quantity if needed
        $product = Product::find($item['product_id']);
        if ($product && $product->is_stock === 'have_stock') {
            $purchaseUnit = Unit::find($item['purchase_unit_id']);
            if ($purchaseUnit) {
                $conversionRate = $purchaseUnit->conversion_rate ?? 1;

                // If the old status was "Received" but new status isn't, we remove stock
                if ($oldStatus === 'Received' && $request->status !== 'Received') {
                    $product->qty -= $item['quantity'] * $conversionRate;
                }
                // If the old status wasn't "Received" but new status is, we add stock
                elseif ($oldStatus !== 'Received' && $request->status === 'Received') {
                    $product->qty += $item['quantity'] * $conversionRate;
                }
                $product->save();
            }
        }
    }

    return redirect()
        ->route('purchases.index')
        ->with('success', 'Purchase updated successfully.');
}

    /**
     * Remove the specified purchase from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->update(['deleted_by' => Auth::id()]);
        $purchase->delete();
    
        return redirect()->route('purchases.index')
                         ->with('success', 'Purchase deleted successfully.');
    }

    public function printAllPurchases(Request $request)
{
    $startDate = \Carbon\Carbon::parse($request->input('start_date', now()->startOfMonth()));
    $endDate   = \Carbon\Carbon::parse($request->input('end_date', now()->endOfMonth()));
    $supplierId = $request->input('supplier_id');

    // Query all purchases in the same date range + optional supplier
    $purchases = Purchase::with('supplier')
        ->whereBetween('purchase_date', [$startDate, $endDate])
        ->when($supplierId, function($query) use ($supplierId) {
            return $query->where('supplier_id', $supplierId);
        })
        ->orderBy('purchase_date', 'desc')
        ->get();

    // Return a special Blade that shows all these purchases for printing
    return view('reports.printallpurchases', compact('purchases', 'startDate', 'endDate'));
}

}
