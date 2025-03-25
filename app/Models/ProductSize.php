<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'size_id', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // public function size() {
    //     return $this->belongsTo(Size::class, 'size_id');
    // }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function product_sizes() {
        return $this->hasMany(ProductSize::class);
    }
   
    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
    
    public function product_toppings() {
        return $this->hasMany(ProductTopping::class);
    }
    
}
