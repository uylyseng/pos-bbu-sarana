<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'table_id',
        'status',
        'subtotal',
        'discount',
        'total',
        'total_item',
        'total_people',
        'product_discount',
        'order_discount',
        'deleted_at',
        'deleted_by',
        'created_by',
        'updated_by'
    ];

    /**
     * Relationship: An order belongs to a customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship: An order belongs to a table.
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relationship: An order was created by a user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function cashier() {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Relationship: An order was last updated by a user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function items()
{
    return $this->hasMany(OrderItem::class);
}
public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

}
