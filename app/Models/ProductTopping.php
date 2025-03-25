<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTopping extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'topping_id', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }
    public function product_sizes() {
        return $this->hasMany(ProductSize::class);
    }
    
    public function product_toppings() {
        return $this->hasMany(ProductTopping::class);
    }
   
    public function order_item_toppings() {
        return $this->hasMany(OrderItemTopping::class);
    }
    
}
