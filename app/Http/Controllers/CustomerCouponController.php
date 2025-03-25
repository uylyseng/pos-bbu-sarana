<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CustomerCouponController extends Controller
{
    /**
     * Display a page to choose coupons for a specific customer.
     */
    public function chooseCoupon(Customer $customer)
    {
        // Get all active coupons (or customize as needed)
        $allCoupons = Coupon::where('status', 'active')->get();

        // Return a view that shows the customer and a list of available coupons
        return view('customers.choose-coupon', compact('customer', 'allCoupons'));
    }

    /**
     * Handle the form submission to assign a coupon to the customer.
     */
    public function assignCoupon(Request $request, Customer $customer)
    {
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
        ]);

        // Attach the selected coupon to this customer without removing existing ones.
        $customer->coupons()->syncWithoutDetaching([$request->coupon_id]);

        return redirect()->back()->with('success', 'Coupon assigned successfully!');
    }

    /**
     * Show the form for editing (reassigning) a coupon for the customer.
     */
    public function editCoupon(Customer $customer, Coupon $coupon)
    {
        // Get all active coupons to allow a replacement
        $allCoupons = Coupon::where('status', 'active')->get();

        return view('customers.edit-coupon', compact('customer', 'coupon', 'allCoupons'));
    }

    /**
     * Update the coupon assignment for the customer.
     * This method detaches the current coupon and assigns a new coupon.
     */
    public function updateCoupon(Request $request, Customer $customer, Coupon $coupon)
    {
        $request->validate([
            'new_coupon_id' => 'required|exists:coupons,id',
        ]);

        // Detach the current coupon assignment.
        $customer->coupons()->detach($coupon->id);

        // Attach the new coupon assignment without detaching other coupons.
        $customer->coupons()->syncWithoutDetaching([$request->new_coupon_id]);

        return redirect()->back()->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove a coupon assignment from a customer.
     */
    public function removeCoupon(Customer $customer, Coupon $coupon)
    {
        $customer->coupons()->detach($coupon->id);

        return redirect()->back()->with('success', 'Coupon removed successfully!');
    }
}
