<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemTopping extends Model
{
    use HasFactory;

    protected $table = 'order_item_topping';

    protected $fillable = [
        'order_item_id',
        'product_topping_id',
        
    ];

    // Relationship to OrderItem
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    // Relationship to ProductTopping
    public function productTopping()
    {
        return $this->belongsTo(ProductTopping::class, 'product_topping_id');
    }
    public function topping() {
        return $this->belongsTo(ProductTopping::class, 'product_topping_id');
    }
    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
}
