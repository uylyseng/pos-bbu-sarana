<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponCustomer extends Pivot
{
    /**
     * The table associated with the pivot model.
     */
    protected $table = 'coupon_customer';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'coupon_id',
        'customer_id',
    ];

    // If you have additional fields in coupon_customer,
    // add them to $fillable as well.
}
