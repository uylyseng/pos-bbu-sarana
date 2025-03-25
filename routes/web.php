<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\OrderUpdated;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;

use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\Reports\PurchaseReport;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ToppingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupTableController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerCouponController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Dashboard\SaleDashboardController;
use App\Http\Controllers\Dashboard\CashierDashboardController;
use App\Http\Controllers\Dashboard\MonthlySaleController;
use App\Http\Controllers\Dashboard\WeeklySaleController;
use App\Http\Controllers\Dashboard\DailySalesController;
use App\Http\Controllers\Dashboard\SalesByCategoryController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\GetTotalPurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard\MostsellproductController;
use App\Http\Controllers\Dashboard\GetTotalExpanseController;
use App\Http\Controllers\Dashboard\GetSumaryController;
use App\Http\Controllers\Dashboard\ProfitlossController;
use App\Http\Controllers\Dashboard\Gettotalworktimecontroller;
use App\Http\Controllers\Dashboard\MostsaleproducttodayController;
use App\Http\Controllers\Dashboard\GetlowstockController;

use App\Http\Controllers\Reports\ShiftsController;
use App\Http\Controllers\Reports\MonthlySale;
use App\Http\Controllers\Reports\SaleController;
use App\Http\Controllers\Reports\Topsellproduct;
use App\Http\Controllers\Reports\DailySaleController;
use App\Http\Controllers\Reports\SaleItemController;
use App\Http\Controllers\Reports\SalesCategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Reports\ExpenseController;


Route::get('/change/{lang}', [LanguageController::class, 'changeLang'])->name('changeLang');

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'auth_login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Other routes
Route::group(['middleware' => 'useradmin'], function () {
    Route::get('/home', [DashboardController::class, 'home'])->name('home');

    Route::resource('users', UserController::class);

    Route::get('roles', [RoleController::class, 'list'])->name('roles.list');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');


    // Other resource routes
    Route::resource('units', UnitController::class);
    Route::resource('toppings', ToppingController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('group_tables', GroupTableController::class);
    Route::resource('tables', TableController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('exchange-rates', ExchangeRateController::class);
    Route::resource('payment_methods', PaymentMethodController::class);
    Route::resource('expense_types', ExpenseTypeController::class);
    Route::resource('expenses', ExpensesController::class);
    Route::put('expenses/{id}/restore', [ExpensesController::class, 'restore'])->name('expenses.restore');
    Route::get('/expenses/recovery', [ExpensesController::class, 'recovery'])->name('expenses.recovery');
    Route::delete('expenses/{id}/force-delete', [ExpensesController::class, 'forceDelete'])->name('expenses.forceDelete');

    // Define the search route BEFORE the products resource route to avoid conflicts.
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

    // Products and related routes
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('stores', StoreController::class);

    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::post('/shifts/close', [ShiftController::class, 'closeShift'])->name('shifts.close');

    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/order', [POSController::class, 'store'])->name('pos.store');




    Route::post('/pos/complete', [PosController::class, 'completeOrder'])->name('pos.complete');
    Route::get('/pos/receipt/{orderId}', [POSController::class, 'showReceipt'])->name('pos.receipt');
    Route::get('/products/{productId}/stock', [ProductController::class, 'getStock']);
    Route::resource('customers', CustomerController::class);
    Route::resource('payments', PaymentController::class);
    // Show the customer display for a specific order

    Route::resource('coupons', CouponController::class);
    Route::get('/customers/{customer}/choose-coupon', [CustomerCouponController::class, 'chooseCoupon'])
    ->name('customers.chooseCoupon');
Route::post('/customers/{customer}/assign-coupon', [CustomerCouponController::class, 'assignCoupon'])
    ->name('customers.assignCoupon');
    Route::get('/customers/{customer}/edit-coupon/{coupon}', [CustomerCouponController::class, 'editCoupon'])->name('customers.editCoupon');
    Route::put('/customers/{customer}/update-coupon/{coupon}', [CustomerCouponController::class, 'updateCoupon'])->name('customers.updateCoupon');
    Route::delete('/customers/{customer}/remove-coupon/{coupon}', [CustomerCouponController::class, 'removeCoupon'])->name('customers.removeCoupon');
    //dashboard
    Route::get('/dashboard/sale', [SaleDashboardController::class, 'index'])->name('dashboard.sale');
    Route::get('/web/monthly-sale', [MonthlySaleController::class, 'getMonthlySale']);
    Route::get('/web/recent-orders', [OrderController::class, 'getRecentOrders']);
    Route::get('/web/sales-by-category', [SalesByCategoryController::class, 'getSalesByCategory']);
    Route::get('/dashboard/total-expense', [GetTotalExpanseController::class, 'getTotalExpense']);
    Route::get('/dashboard/summary', [GetSumaryController::class, 'getSummary']);
    Route::get('/dashboard/profit-loss', [ProfitlossController::class, 'getProfitLoss']);
    Route::get('/dashboard/cashier', [CashierDashboardController::class, 'index'])->name('dashboard.cashier');
    Route::get('/web/weekly-sale', [WeeklySaleController::class, 'getMondayToSundaySale'])->name('weekly.sale');
    Route::get('/reports/daily-sales', [DailySalesController::class, 'getDailySales'])->name('reports.daily-sales');
    Route::get('/low-stock-count', [SaleDashboardController::class, 'getLowStockCount']);
    Route::get('/dashboard/most-sell-product', [MostsellproductController::class, 'getMostSoldProducts']);
    Route::get('/dashboard/work-time-summary', [Gettotalworktimecontroller::class, 'getWorkTimeSummary']);
    Route::get('/dashboard/most-sold-product-today', [MostsaleproducttodayController::class, 'getMostSoldProductToday']);
    Route::get('/dashboard/low-stock-count', [GetlowstockController::class, 'getLowStockCount']);
  // Purchase Report Route
Route::get('/reports/purchases', [PurchaseReport::class, 'purchaseReport'])->name('reports.purchases');
Route::get('/purchase/{purchaseId}/preview', [PurchaseReport::class, 'purchasePreview'])->name('reports.purchasepreview');
Route::get('/reports/topsellingproduct', [Topsellproduct::class, 'topSellingProducts'])->name('reports.topsellingproduct');

Route::get('/reports/daily-sales', [DailySaleController::class, 'dailySalesReport'])->name('reports.daily-sales');
//
Route::get('/reports/sales-by-category', [SalesCategoryController::class, 'salesCategoryReport'])->name('reports.sales-by-category');
Route::get('/web/total-purchase', [GetTotalPurchaseController::class, 'getTotalPurchase']);
Route::get('/customer-screen', function () {
    return view('pos.customerscreen');
})->name('customerscreen');
Route::post('/order-updated', function (Request $request) {
    $order = $request->input('order');
    event(new OrderUpdated($order));
    return response()->json(['status' => 'success']);
});
Route::get('/register/details', [ShiftController::class, 'getRegisterDetail'])->name('register.details');


Route::get('reports/purchases/print', [PurchaseReport::class, 'printPurchases'])
     ->name('reports.purchasesprint');


     Route::get('/reports/sale_item', [SaleitemController::class, 'saleItemReport'])
     ->name('reports.sale_item');

     Route::get('/reports/shifts', [ShiftsController::class, 'shiftReport'])
     ->name('reports.shifts');


     Route::get('/reports/shift/{shiftId}/{userId}/print', [ShiftsController::class, 'printShiftReport'])->name('shift.print');
     Route::get('/reports/monthly-sales', [MonthlySale::class, 'monthlySalesReport'])->name('reports.monthly-sales');
     Route::get('/reports/sale-items', [SaleItemController::class, 'saleitems'])->name('reports.sale-items');
     Route::get('/reports/print-sale-items', [SaleItemController::class, 'printSaleItems'])->name('reports.print-sale-items');
     Route::get('/reports/sales', [SaleController::class, 'sales'])
     ->name('reports.sales');
     Route::get('/reports/sales/print', [SaleController::class, 'printSale'])
     ->name('reports.print_sales');
     Route::get('/sales/list', [SalesController::class, 'listSales'])->name('sales.list');
     Route::get('/sales/today', [SalesController::class, 'getSaleToday'])->name('sales.today');
     Route::get('/reports/expenses', [ExpenseController::class, 'expenseReport'])->name('reports.expenses');
     Route::get('/reports/expenses/print', [ExpenseController::class, 'printExpense'])->name('reports.print_expenses');
     Route::get('/reports/daily-sales/print', [DailySaleController::class, 'printDailySalesReport'])->name('reports.print_dailysale');
     Route::get('/reports/monthly-sales/print', [MonthlySale::class, 'printMonthlySalesReport'])->name('reports.print_monthlysale');
     Route::get('/reports/print/topselling-product', [Topsellproduct::class, 'printTopSellingProducts'])->name('reports.print_topselling');
     Route::get('/reports/print-sales-category', [SalesCategoryController::class, 'printSalesByCategory'])->name('reports.print_salebycategory');

     Route::get('/reports/purchases/print', [PurchaseReport::class, 'printPurchases'])
     ->name('reports.purchases_print');
     Route::get('/reports/purchase/printpreview/{purchaseId}', [PurchaseReport::class, 'printPurchasepreview'])
    ->name('reports.purchasepreviewprint');
    Route::post('/pos/hold-order', [POSController::class, 'holdOrder'])->name('pos.holdOrder');
Route::get('/pos/load-held-order', [POSController::class, 'loadHeldOrder'])->name('pos.loadHeldOrder');
Route::post('/payments/complete', [POSController::class, 'completePayment'])->name('payments.complete');
Route::post('/pos/update-held-order-payment', [POSController::class, 'updateHeldOrderPayment'])->name('pos.updateHeldOrderPayment');
Route::put('/pos/update-hold', [PosController::class, 'updateHold'])
    ->name('pos.updateHold');
    Route::post('/payments/update', [PaymentController::class, 'updatePayment'])
    ->name('payments.updatePayment');
    });
