<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the query string, defaulting to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Retrieve coupons ordered by creation date (ascending) with pagination
        $coupons = Coupon::orderBy('created_at', 'asc')->paginate($perPage);
    
        // Return the view with coupons and the selected 'perPage' value
        return view('coupons.index', compact('coupons', 'perPage'));
    }
    

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code',
            'discount'    => 'required|numeric|min:0', // Use min:0; adjust max if needed
            'start_date'  => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
            'status'      => 'required|in:active,inactive',
        ]);

        Coupon::create($validated);

        return redirect()->route('coupons.index')
                         ->with('success', 'Coupon created successfully.');
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount'    => 'required|numeric|min:0',
            'start_date'  => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
            'status'      => 'required|in:active,inactive',
        ]);

        $coupon->update($validated);

        return redirect()->route('coupons.index')
                         ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')
                         ->with('success', 'Coupon deleted successfully.');
    }
}
