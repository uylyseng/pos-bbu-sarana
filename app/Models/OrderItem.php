<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_size_id',
        'product_discount',      
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * Relationship: An order item belongs to an order.
     */
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: An order item belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: An order item may have a size.
     */
    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }
    // public function toppings()
    // {
    //     return $this->hasMany(OrderItemTopping::class, 'order_item_id');
    // }

    public function toppings()
    {
        // Each OrderItem can have many pivot rows in order_item_toppings
        return $this->hasMany(OrderItemTopping::class, 'order_item_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }
    
    /**
     * Relationship: An order item may have a topping.
     */
    // public function productTopping()
    // {
    //     return $this->belongsTo(ProductTopping::class, 'product_topping_id');
    // }
    
}
