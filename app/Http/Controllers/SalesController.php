<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function listSales(Request $request)
    {
        // 1. Get filters from request
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // 2. Build base query
        $query = DB::table('orders as o')
            ->leftJoin('tables as t', 'o.table_id', '=', 't.id')
            ->leftJoin('payments as p', 'o.id', '=', 'p.order_id')
            ->select(
                'o.id',
                DB::raw('DATE(o.created_at) as sale_date'),
                't.name as table_name',
                'o.status as order_status',
                'o.discount',
                'o.total',
                'p.amount as paid',
                'p.changeUSD as change_usd',
                'p.status as payment_status'
            )
            ->orderBy('o.created_at', 'desc');
    
        // 3. Apply search filter (optional)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('t.name', 'like', '%' . $search . '%')
                  ->orWhere('o.status', 'like', '%' . $search . '%')
                  ->orWhere('p.status', 'like', '%' . $search . '%');
            });
        }
    
        // 4. Paginate and return view
        $sales = $query->paginate($perPage)->appends([
            'search' => $search,
            'per_page' => $perPage,
        ]);
    
        return view('sales.list', compact('sales', 'search', 'perPage'));
    }
    public function getSaleToday(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
        $search = $request->input('search');
    
        // Build the base query for today's sales
        $query = DB::table('orders as o')
            ->leftJoin('tables as t', 'o.table_id', '=', 't.id')
            ->leftJoin('payments as p', 'o.id', '=', 'p.order_id')
            ->select(
                'o.id',
                DB::raw('DATE(o.created_at) as sale_date'),
                't.name as table_name',
                'o.status as order_status',
                'o.discount',
                'o.total',
                'p.amount as paid',
                'p.changeUSD as change_usd',
                'p.status as payment_status'
            )
            ->whereDate('o.created_at', $today)
            ->orderBy('o.created_at', 'desc');
    
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('t.name', 'like', '%' . $search . '%')
                  ->orWhere('o.status', 'like', '%' . $search . '%')
                  ->orWhere('p.status', 'like', '%' . $search . '%');
            });
        }
    
        // Paginate results and pass search parameter to pagination links
        $sales = $query->paginate($perPage)->appends([
            'per_page' => $perPage,
            'search' => $search,
        ]);
    
        return view('sales.today', compact('sales', 'perPage', 'today', 'search'));
    }
    

    
}
