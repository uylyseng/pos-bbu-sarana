<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments'; // Define table name (optional if it follows Laravel naming convention)

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'amount',
        'changeUSD',
        'changeRiel',
        'payment_date',
        'notes',
        'status'
    ];

    // Define relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function shift() {
        return $this->belongsTo(Shift::class);
    }
}
