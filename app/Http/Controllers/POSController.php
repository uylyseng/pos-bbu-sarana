<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\GroupTable;
use App\Models\Table;
use App\Models\Topping;
use App\Models\Size;
use App\Models\ProductSize;
use App\Models\ProductTopping;
use App\Models\OrderItemTopping;
use App\Models\Shift;
use App\Models\Coupon;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class POSController extends Controller
{
    /**
     * Display the POS system.
     */
    public function index()
    {
        // 1. Fetch the latest exchange rate from the DB (or default to 4100)
        $latestRate = DB::table('exchange_rates')->orderBy('id', 'desc')->value('rate');
        $exchangeRate = $latestRate ?? 4100;
    
        // 2. Get all payment methods
        $paymentMethods = DB::table('payment_methods')->get();
    
        // 3. Attempt to fetch the user's active shift (where time_close is null)
        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('time_close')
            ->first();
    
        // 4. Get customers with their coupon relationships eagerly loaded
        $customers = Customer::with('coupons')->get();

    
        // Optionally, get all coupons if needed elsewhere (e.g., for assignment)
        $coupons = Coupon::all();
        
    
        // 5. Return the Blade view with all required data
        return view('pos.index', [
            'customers'      => $customers,
            'paymentMethods' => $paymentMethods,
            'tables'         => Table::all(),
            'categories'     => Category::where('status', 'active')->with([
                'products' => function ($query) {
                    $query->where('active', 'active')
                          ->with(['product_sizes.size', 'product_toppings.topping']);
                }
            ])->get(),
            'tableGroups'    => GroupTable::with('tables')->get(),
            'exchangeRate'   => $exchangeRate,
            'shift'          => $shift,
            'coupons'        => $coupons,
        ]);
        
    }
    

public function showProfile() {
    $user = Auth::user();
    return view('profile', compact('user'));
}


public function store(Request $request)
{
    $request->validate([
        'customer_id'      => 'nullable|exists:customers,id',
        'table_id'         => 'nullable|exists:tables,id',
        'discount'         => 'nullable|numeric|min:0',
        'product_discount' => 'nullable|numeric|min:0',
        'order_discount'   => 'nullable|numeric|min:0',
        'total'            => 'required|numeric|min:0',
        'total_item'       => 'nullable|integer|min:1',
        'total_people'     => 'nullable|integer|min:1',
        'items'            => 'required|array|min:1',
    ]);

    try {
        DB::beginTransaction();

        // 1) Compute subtotal, total items, and sum of product discounts from the request.
        $subtotal = collect($request->items)->sum(function ($item) {
            return ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
        });
        $totalItemCount = collect($request->items)->sum(function ($item) {
            return $item['quantity'] ?? 0;
        });
        $sumProductDiscount = collect($request->items)->sum(function ($item) {
            return $item['product_discount'] ?? 0;
        });
        
        // 2) Check if the customer has an active coupon.
        $couponDiscount = 0;
        if ($request->customer_id) {
            $customer = Customer::with('coupons')->find($request->customer_id);
            if ($customer) {
                $activeCoupon = $customer->activeCoupon();
                if ($activeCoupon && $activeCoupon->isValid()) {
                    $couponDiscount = $activeCoupon->discount; // e.g., 50 for 50%
                }
            }
        }

        // 3) Decide which discount method to use:
        // If an order discount (or coupon) is provided (or active), use order-level discount.
        // Otherwise, use the sum of product discounts.
        if ($couponDiscount > 0 || (!empty($request->order_discount) && $request->order_discount > 0)) {
            if ($couponDiscount > 0) {
                // Use coupon discount.
                $orderDiscount = $subtotal * ($couponDiscount / 100);
            } else {
                // Use manual order discount provided in 'order_discount'.
                $orderDiscount = $request->order_discount;
            }
            $totalAfterDiscount = max(0, $subtotal - $orderDiscount);
            $orderDiscountField = $orderDiscount;
            $productDiscountField = 0;
            $totalDiscount = $orderDiscount;
        } else {
            // Use the sum of product discounts from the request items.
            $totalAfterDiscount = max(0, $subtotal - $sumProductDiscount);
            $orderDiscountField = 0;
            $productDiscountField = $sumProductDiscount;
            $totalDiscount = $sumProductDiscount;
        }
        
        // 4) Create the Order.
        $order = Order::create([
            'customer_id'      => $request->customer_id,
            'table_id'         => $request->table_id,
            'status'           => 'complete',
            'subtotal'         => $subtotal,
            'discount'         => $totalDiscount,
            'order_discount'   => $orderDiscountField,
            'product_discount' => $productDiscountField,
            'total'            => $totalAfterDiscount,
            'total_item'       => $totalItemCount,
            'total_people'     => $request->total_people ?? 1,
            'created_by'       => Auth::id(),
        ]);

        // 5) Process each Order Item.
        $calculatedSumProductDiscount = 0;
        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            if (!$product) {
                continue;
            }
            if (!in_array($product->is_stock, ['have_stock', 'none_stock'])) {
                DB::rollBack();
                return response()->json(['message' => "Invalid stock type for product: {$product->name}"], 400);
            }
            if ($product->is_stock === 'have_stock') {
                if ($product->qty < ($itemData['quantity'] ?? 1)) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Insufficient stock for product: {$product->name}",
                    ], 400);
                }
                $product->decrement('qty', $itemData['quantity']);
            }

            // Look up product_size_id if size_id is provided
            $actualProductSizeId = null;
            if (!empty($itemData['size_id'])) {
                $productSize = ProductSize::where('product_id', $product->id)
                                          ->where('size_id', $itemData['size_id'])
                                          ->first();
                if ($productSize) {
                    $actualProductSizeId = $productSize->id;
                }
            }

            // Create the OrderItem (outside of if-block so it is created even if size_id is empty)
            $orderItem = OrderItem::create([
                'order_id'         => $order->id,
                'product_id'       => $product->id,
                'product_size_id'  => $actualProductSizeId,
                'quantity'         => $itemData['quantity'] ?? 1,
                'unit_price'       => $itemData['unit_price'] ?? 0,
                'product_discount' => $itemData['product_discount'] ?? 0,
                'subtotal'         => ($itemData['unit_price'] ?? 0) * ($itemData['quantity'] ?? 1),
            ]);

            // Check that order item was created
            if (!$orderItem || !$orderItem->id) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to create order item.'
                ], 500);
            }

            $calculatedSumProductDiscount += $orderItem->product_discount;

            // Handle single/multiple toppings
            $rawToppingIds = $itemData['topping_id'] ?? []; // Could be ['1','2'] or a single value
            if (!is_array($rawToppingIds)) {
                // Convert single value to array
                $rawToppingIds = [$rawToppingIds];
            }
            
            foreach ($rawToppingIds as $toppingCode) {
                // If the user sent an empty or invalid code, skip
                if (!$toppingCode) {
                    continue;
                }
            
                // Look up the product_topping row by product_id and topping_id
                $productTopping = ProductTopping::where('product_id', $product->id)
                    ->where('topping_id', $toppingCode)
                    ->first();
            
                // If found, store the pivot record with the real primary key
                if ($productTopping) {
                    OrderItemTopping::create([
                        'order_item_id'      => $orderItem->id,
                        'product_topping_id' => $productTopping->id,
                    ]);
                }
            }
        }

        // 6) For non-order-level discounts, update the Order's product_discount
        if (empty($request->order_discount) || $request->order_discount == 0) {
            $order->update(['product_discount' => round($calculatedSumProductDiscount, 2)]);
            $totalAfterDiscount = max(0, $subtotal - $calculatedSumProductDiscount);
            $order->update(['total' => $totalAfterDiscount]);
        }

        DB::commit();

        return response()->json([
            'message'  => 'Order created successfully!',
            'order_id' => $order->id,
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Error creating order',
            'error'   => $e->getMessage(),
        ], 500);
    }
}



    public function showReceipt($orderId)
    {
        // Fetch the order with all its related details.
        $order = Order::with([
            'customer',               // Customer associated with the order.
            'table',                  // Table associated with the order.
            'items',                  // Order items.
            'items.product',          // Product details for each order item.
            'items.productSize',      // Product size for each order item.
            'items.productSize.size', // Size details for each product size.
            'items.toppings',         // Toppings for each order item.
            'items.toppings.topping', // Topping details for each topping.
        ])->findOrFail($orderId);
        
        // Fetch the latest Payment for this order.
        $payment = Payment::where('order_id', $orderId)->latest()->first();
        
        // Retrieve the exchange rate from the exchange_rates table.
        // Assumes the table has a field 'rate'
        $exchangeRate = DB::table('exchange_rates')->value('rate');
        
        // Return the receipt view with order, payment, and exchange rate details.
        return view('pos.receipt', compact('order', 'payment', 'exchangeRate'));
    }
    

    public function holdOrder(Request $request)
    {
        $request->validate([
            'customer_id'      => 'nullable|exists:customers,id',
            'table_id'         => 'required|exists:tables,id',  // Require table id for holding an order.
            'discount'         => 'nullable|numeric|min:0',
            'product_discount' => 'nullable|numeric|min:0',
            'order_discount'   => 'nullable|numeric|min:0',
            'total'            => 'required|numeric|min:0',
            'total_item'       => 'nullable|integer|min:1',
            'total_people'     => 'nullable|integer|min:1',
            'items'            => 'required|array|min:1',
        ]);
    
        try {
            DB::beginTransaction();
    
            // 1. Compute subtotal, total items, and product discounts from the request.
            $subtotal = collect($request->items)->sum(function ($item) {
                return ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
            });
            $totalItemCount = collect($request->items)->sum(function ($item) {
                return $item['quantity'] ?? 0;
            });
            $sumProductDiscount = collect($request->items)->sum(function ($item) {
                return $item['product_discount'] ?? 0;
            });
    
            // 2. Check if the customer has an active coupon.
            $couponDiscount = 0;
            if ($request->customer_id) {
                $customer = Customer::with('coupons')->find($request->customer_id);
                if ($customer) {
                    $activeCoupon = $customer->activeCoupon();
                    if ($activeCoupon && $activeCoupon->isValid()) {
                        $couponDiscount = $activeCoupon->discount; // e.g., 50 for 50%
                    }
                }
            }
    
            // 3. Decide discount method.
            if ($couponDiscount > 0 || (!empty($request->order_discount) && $request->order_discount > 0)) {
                if ($couponDiscount > 0) {
                    $orderDiscount = $subtotal * ($couponDiscount / 100);
                } else {
                    $orderDiscount = $request->order_discount;
                }
                $totalAfterDiscount = max(0, $subtotal - $orderDiscount);
                $orderDiscountField = $orderDiscount;
                $productDiscountField = 0;
                $totalDiscount = $orderDiscount;
            } else {
                $totalAfterDiscount = max(0, $subtotal - $sumProductDiscount);
                $orderDiscountField = 0;
                $productDiscountField = $sumProductDiscount;
                $totalDiscount = $sumProductDiscount;
            }
    
            // 4. Create the Order with status "hold".
            $order = Order::create([
                'customer_id'      => $request->customer_id,
                'table_id'         => $request->table_id,
                'status'           => 'hold',  // Mark order as held.
                'subtotal'         => $subtotal,
                'discount'         => $totalDiscount,
                'order_discount'   => $orderDiscountField,
                'product_discount' => $productDiscountField,
                'total'            => $totalAfterDiscount,
                'total_item'       => $totalItemCount,
                'total_people'     => $request->total_people ?? 1,
                'created_by'       => Auth::id(),
            ]);
    
            // 5. Process each Order Item.
            $calculatedSumProductDiscount = 0;
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                if (!$product) {
                    continue;
                }
                if (!in_array($product->is_stock, ['have_stock', 'none_stock'])) {
                    DB::rollBack();
                    return response()->json(['message' => "Invalid stock type for product: {$product->name}"], 400);
                }
                if ($product->is_stock === 'have_stock') {
                    if ($product->qty < ($itemData['quantity'] ?? 1)) {
                        DB::rollBack();
                        return response()->json([
                            'message' => "Insufficient stock for product: {$product->name}",
                        ], 400);
                    }
                    $product->decrement('qty', $itemData['quantity']);
                }
    
                $actualProductSizeId = null;
                if (!empty($itemData['size_id'])) {
                    $productSize = ProductSize::where('product_id', $product->id)
                                              ->where('size_id', $itemData['size_id'])
                                              ->first();
                    if ($productSize) {
                        $actualProductSizeId = $productSize->id;
                    }
                }
    
                $orderItem = OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $product->id,
                    'product_size_id'  => $actualProductSizeId,
                    'quantity'         => $itemData['quantity'] ?? 1,
                    'unit_price'       => $itemData['unit_price'] ?? 0,
                    'product_discount' => $itemData['product_discount'] ?? 0,
                    'subtotal'         => ($itemData['unit_price'] ?? 0) * ($itemData['quantity'] ?? 1),
                ]);
    
                if (!$orderItem || !$orderItem->id) {
                    DB::rollBack();
                    return response()->json(['message' => 'Failed to create order item.'], 500);
                }
    
                $calculatedSumProductDiscount += $orderItem->product_discount;
    
                $rawToppingIds = $itemData['topping_id'] ?? [];
                if (!is_array($rawToppingIds)) {
                    $rawToppingIds = [$rawToppingIds];
                }
                
                foreach ($rawToppingIds as $toppingCode) {
                    if (!$toppingCode) continue;
                    $productTopping = ProductTopping::where('product_id', $product->id)
                        ->where('topping_id', $toppingCode)
                        ->first();
                    if ($productTopping) {
                        OrderItemTopping::create([
                            'order_item_id'      => $orderItem->id,
                            'product_topping_id' => $productTopping->id,
                        ]);
                    }
                }
            }
    
            if (empty($request->order_discount) || $request->order_discount == 0) {
                $order->update(['product_discount' => round($calculatedSumProductDiscount, 2)]);
                $totalAfterDiscount = max(0, $subtotal - $calculatedSumProductDiscount);
                $order->update(['total' => $totalAfterDiscount]);
            }
    
            // 6. Update the status of the table to "occupid" and save held order total.
            DB::table('tables')->where('id', $request->table_id)->update([
                'status' => 'occupid',
                // Using the computed total after discount.
            ]);
    
            DB::commit();
    
            return response()->json([
                'message'  => 'Order held successfully!',
                'order_id' => $order->id,
             
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error holding order',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    

    public function loadHeldOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
        ]);
    
        $tableId = $request->input('table_id');
    
        $order = Order::with([
            'customer',
            'table',
            'items',               // Order items relationship
            'items.product',       // Associated product details
            'items.productSize',   // Full ProductSize model (includes product_size_id)
            'items.productSize.size', // Nested Size details (name, etc.)
            'items.toppings',      // OrderItemTopping pivot records
            'items.toppings.topping', // Topping details for each pivot record
        ])
        ->where('table_id', $tableId)
        ->where('status', 'hold')
        ->latest()
        ->first();
    
        if (!$order) {
            return response()->json(['message' => 'No held order found for this table.'], 404);
        }
    
        return response()->json(['order' => $order], 200);
    }
    
    public function updateHeldOrderPayment(Request $request)
{
    $request->validate([
        'order_id'           => 'required|exists:orders,id',
        'payment_method_id'  => 'required|exists:payment_methods,id',
        'amount'             => 'required|numeric|min:0',
        'changeUSD'          => 'required|numeric|min:0',
        'changeRiel'         => 'required|numeric|min:0',
        'note'               => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        // Retrieve the held order.
        $order = Order::findOrFail($request->order_id);

        // Update order status to complete.
        $order->update(['status' => 'complete']);

        // Create a Payment record.
        $payment = Payment::create([
            'order_id'          => $order->id,
            'payment_method_id' => $request->payment_method_id,
            'amount'            => $request->amount,
            'changeUSD'         => $request->changeUSD,
            'changeRiel'        => $request->changeRiel,
            'note'              => $request->note,
            'status'            => 'completed',
        ]);

        // Update the table: set its status to active and clear held order total.
        if ($order->table_id) {
            DB::table('tables')->where('id', $order->table_id)->update([
                'status'           => 'active',
                
            ]);
        }

        DB::commit();

        return response()->json([
            'message'  => 'Held order updated and payment completed successfully.',
            'order_id' => $order->id,
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Error updating held order payment.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

    
public function updateHold(Request $request)
{
    $request->validate([
        'order_id'         => 'required|exists:orders,id',
        'customer_id'      => 'nullable|exists:customers,id',
        'table_id'         => 'nullable|exists:tables,id',
        'discount'         => 'nullable|numeric|min:0',
        'product_discount' => 'nullable|numeric|min:0',
        'order_discount'   => 'nullable|numeric|min:0',
        'total'            => 'required|numeric|min:0',
        'total_item'       => 'nullable|integer|min:1',
        'total_people'     => 'nullable|integer|min:1',
        'items'            => 'required|array|min:1',
    ]);

    try {
        DB::beginTransaction();

        // Find the existing held order.
        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 1) Compute subtotal, total items, and sum of product discounts from the request items.
        $subtotal = collect($request->items)->sum(function ($item) {
            return ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
        });
        $totalItemCount = collect($request->items)->sum(function ($item) {
            return $item['quantity'] ?? 0;
        });
        $sumProductDiscount = collect($request->items)->sum(function ($item) {
            return $item['product_discount'] ?? 0;
        });
        
        // 2) Check if the customer has an active coupon.
        $couponDiscount = 0;
        if ($request->customer_id) {
            $customer = Customer::with('coupons')->find($request->customer_id);
            if ($customer) {
                $activeCoupon = $customer->activeCoupon();
                if ($activeCoupon && $activeCoupon->isValid()) {
                    $couponDiscount = $activeCoupon->discount; // e.g., 50 for 50%
                }
            }
        }

        // 3) Decide which discount method to use:
        if ($couponDiscount > 0 || (!empty($request->order_discount) && $request->order_discount > 0)) {
            if ($couponDiscount > 0) {
                $orderDiscount = $subtotal * ($couponDiscount / 100);
            } else {
                $orderDiscount = $request->order_discount;
            }
            $totalAfterDiscount = max(0, $subtotal - $orderDiscount);
            $orderDiscountField = $orderDiscount;
            $productDiscountField = 0;
            $totalDiscount = $orderDiscount;
        } else {
            $totalAfterDiscount = max(0, $subtotal - $sumProductDiscount);
            $orderDiscountField = 0;
            $productDiscountField = $sumProductDiscount;
            $totalDiscount = $sumProductDiscount;
        }
        
        // 4) Prepare data for updating the Order.
        $updateData = [
            'customer_id'      => $request->customer_id,
            'status'           => 'complete', // Mark held order as complete.
            'subtotal'         => $subtotal,
            'discount'         => $totalDiscount,
            'order_discount'   => $orderDiscountField,
            'product_discount' => $productDiscountField,
            'total'            => $totalAfterDiscount,
            'total_item'       => $totalItemCount,
            'total_people'     => $request->total_people ?? 1,
            'created_by'       => Auth::id(),
        ];

        if (!empty($request->table_id)) {
            $updateData['table_id'] = $request->table_id;
        }
        // Update table status to active if table_id is provided.
        if (!empty($request->table_id)) {
            $table = Table::find($request->table_id);
            if ($table) {
                $table->update(['status' => 'active']);
            }
        }

        // 5) Update the Order.
        $order->update($updateData);

        // 6) Remove old order items and re-create them.
        $order->orderItems()->delete();

        $calculatedSumProductDiscount = 0;
        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            if (!$product) {
                continue;
            }
            if (!in_array($product->is_stock, ['have_stock', 'none_stock'])) {
                DB::rollBack();
                return response()->json(['message' => "Invalid stock type for product: {$product->name}"], 400);
            }
            // Removed stock decrement here since stock was already handled when order was held.

            $actualProductSizeId = null;
            if (!empty($itemData['size_id'])) {
                $productSize = ProductSize::where('product_id', $product->id)
                                          ->where('size_id', $itemData['size_id'])
                                          ->first();
                if ($productSize) {
                    $actualProductSizeId = $productSize->id;
                }
            }

            $orderItem = OrderItem::create([
                'order_id'         => $order->id,
                'product_id'       => $product->id,
                'product_size_id'  => $actualProductSizeId,
                'quantity'         => $itemData['quantity'] ?? 1,
                'unit_price'       => $itemData['unit_price'] ?? 0,
                'product_discount' => $itemData['product_discount'] ?? 0,
                'subtotal'         => ($itemData['unit_price'] ?? 0) * ($itemData['quantity'] ?? 1),
            ]);

            if (!$orderItem || !$orderItem->id) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to create order item.'], 500);
            }

            $calculatedSumProductDiscount += $orderItem->product_discount;

            $rawToppingIds = $itemData['topping_id'] ?? [];
            if (!is_array($rawToppingIds)) {
                $rawToppingIds = [$rawToppingIds];
            }
            foreach ($rawToppingIds as $toppingCode) {
                if (!$toppingCode) {
                    continue;
                }
                $productTopping = ProductTopping::where('product_id', $product->id)
                    ->where('topping_id', $toppingCode)
                    ->first();
                if ($productTopping) {
                    OrderItemTopping::create([
                        'order_item_id'      => $orderItem->id,
                        'product_topping_id' => $productTopping->id,
                    ]);
                }
            }
        }

        if (empty($request->order_discount) || $request->order_discount == 0) {
            $order->update(['product_discount' => round($calculatedSumProductDiscount, 2)]);
            $totalAfterDiscount = max(0, $subtotal - $calculatedSumProductDiscount);
            $order->update(['total' => $totalAfterDiscount]);
        }

        DB::commit();

        return response()->json([
            'message'  => 'Held order updated successfully!',
            'order_id' => $order->id,
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Error updating held order',
            'error'   => $e->getMessage(),
        ], 500);
    }
}


    
}
 