<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contact_info',
    ];
    // In App\Models\Customer
// Customer.php
public function coupons()
{
    return $this->belongsToMany(Coupon::class, 'coupon_customer', 'customer_id', 'coupon_id');
}

// Customer.php
public function activeCoupon()
{
    return $this->coupons()
        ->where('status', 'active')
        ->where(function($query) {
            $query->where('start_date', '<=', now())
                  ->orWhereNull('start_date');
        })
        ->where(function($query) {
            $query->where('expire_date', '>=', now())
                  ->orWhereNull('expire_date');
        })
        ->first();
}


// Customer.php
}
