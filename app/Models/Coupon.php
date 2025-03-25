<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'start_date',
        'expire_date',
        'status',
    ];

    protected $casts = [
        'start_date'  => 'datetime',
        'expire_date' => 'datetime',
    ];


    // In App\Models\Coupon
public function customers()
{
    return $this->belongsToMany(
        Customer::class,
        'coupon_customer',
        'coupon_id',
        'customer_id'
    )->using(CouponCustomer::class);
}
public function isValid()
{
    $now = now();
    return $this->status === 'active' &&
           $this->start_date <= $now &&
           $this->expire_date >= $now;
}

}
