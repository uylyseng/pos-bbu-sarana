<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SaleDashboardController extends Controller
{
    public function index()
    {
        // Retrieve orders from the last month
        $orders = Order::where('created_at', '>=', now()->subMonth())->get();

        // Calculate revenue, cost, and profit from the retrieved orders.
        // (Assuming your orders have 'total_price' and 'cost_price' columns)
        $totalRevenue = $orders->sum('total_price');
        $totalCost = $orders->sum('cost_price');
        $profit = $totalRevenue - $totalCost;

        // Alternatively, if you want to use the 'total' column (as per your SQL query) to get monthly total sales:
        $totalSales = Order::where('created_at', '>=', now()->subMonth())->sum('total');
          // Example query to get the most sold product overall
          $mostSoldProduct = OrderItem::select('product_id')
          ->selectRaw('SUM(quantity) as total_sold')
          ->groupBy('product_id')
          ->orderByDesc('total_sold')
          ->with('product') // Assuming relation: OrderItem belongs to Product
          ->first();

      // Optionally, get last week's sold quantity for the most sold product
      $mostSoldProduct = OrderItem::join('products as p', 'order_items.product_id', '=', 'p.id')
      ->select('p.name_en as product_name', DB::raw('SUM(order_items.quantity) as total_quantity'))
      ->where('order_items.created_at', '>=', now()->subWeek())
      ->groupBy('order_items.product_id', 'p.name_en')
      ->orderByDesc('total_quantity')
      ->limit(1)
      ->first();
  
  if ($mostSoldProduct) {
      $mostSoldProduct->last_week_sold = $mostSoldProduct->total_quantity;
  }
  // ✅ Fixed: Low Stock Count (Ensuring 'have_stock' is used correctly)
  $lowStockCount = $this->getLowStockCount();

        // Return data to the view. You can pass any of these as needed.
        return view('dashboard.sale', compact('orders', 'totalRevenue', 'totalCost', 'profit', 'totalSales', 'mostSoldProduct', 'lowStockCount'));
    }
    public function getLowStockCount()
    {
        return Product::where('is_stock', 'have_stock') // Ensure 'is_stock' is the correct enum value
            ->where('active', 1)
            ->whereRaw('qty <= low_stock') // Using whereRaw for correct comparison
            ->count();
    }
    
    
}
