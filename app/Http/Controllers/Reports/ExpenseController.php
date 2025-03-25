<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function expenseReport(Request $request)
    {
        // 1. Handle pagination
        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // 2. Get date filters
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
        $endDate   = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));
    
        // 3. Get user/payment_method filters
        $userId = $request->input('user_id');
        $paymentMethodId = $request->input('payment_method_id');
    
        // 4. Build query
        $query = DB::table('expenses as e')
            ->leftJoin('users as u', 'e.created_by', '=', 'u.id')
            ->leftJoin('payment_methods as pm', 'e.payment_method_id', '=', 'pm.id')
            ->select(
                'e.id as expense_id',
                'e.expense_date as date',
                'e.reference',
                'u.name as created_by',
                'e.description',
                'pm.name as payment_method',
                'e.amount as total_amount'
            )
            ->whereNull('e.deleted_at')
            ->whereBetween('e.expense_date', [$startDate, $endDate]);
    
        // 5. Apply filters if set
        if (!empty($userId)) {
            $query->where('e.created_by', $userId);
        }
    
        if (!empty($paymentMethodId)) {
            $query->where('e.payment_method_id', $paymentMethodId);
        }
    
        // 6. Paginate and append query parameters
        $expenses = $query->orderByDesc('e.expense_date')
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'user_id' => $userId,
                'payment_method_id' => $paymentMethodId,
            ]);
    
        // 7. Load filter options for dropdowns
        $users = DB::table('users')->select('id', 'name')->get();
        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();
    
        // 8. Return the view
        return view('reports.expenses', compact(
            'expenses',
            'perPage',
            'startDate',
            'endDate',
            'users',
            'paymentMethods',
            'userId',
            'paymentMethodId'
        ));
    }
    public function printExpense(Request $request)
{
    // 1. Get filters
    $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
    $endDate   = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));
    $userId = $request->input('user_id');
    $paymentMethodId = $request->input('payment_method_id');

    // 2. Build query
    $query = DB::table('expenses as e')
        ->leftJoin('users as u', 'e.created_by', '=', 'u.id')
        ->leftJoin('payment_methods as pm', 'e.payment_method_id', '=', 'pm.id')
        ->select(
            'e.id as expense_id',
            'e.expense_date as date',
            'e.reference',
            'u.name as created_by',
            'e.description',
            'pm.name as payment_method',
            'e.amount as total_amount'
        )
        ->whereNull('e.deleted_at')
        ->whereBetween('e.expense_date', [$startDate, $endDate]);

    // 3. Apply filters
    if (!empty($userId)) {
        $query->where('e.created_by', $userId);
    }

    if (!empty($paymentMethodId)) {
        $query->where('e.payment_method_id', $paymentMethodId);
    }

    // 4. Execute query
    $expenses = $query->orderByDesc('e.expense_date')->get();

    // 5. Return print Blade view
    return view('reports.print_expenses', [
        'expenses' => $expenses,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ]);
}

}
