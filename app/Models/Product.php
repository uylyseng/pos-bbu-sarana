<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode', 'name_en', 'name_kh', 'base_price', 'purchase_unit_id','sale_unit_id' ,'category_id',
        'is_stock', 'qty', 'image', 'active', 'low_stock', 'has_size', 'has_topping', 'created_by',
        'updated_by','deleted_by'
    ];

    // Relationship with Unit
    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }
    public function unit()
    {
        return $this->purchaseUnit();
    }
    /**
     * Get the sale unit associated with the product.
     */
    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }
    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }

    // Relationship with ProductSize
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // Relationship with ProductTopping
    public function toppings()
    {
        return $this->hasMany(ProductTopping::class);
    }
    public function product_sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function product_toppings()
    {
        return $this->hasMany(ProductTopping::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'product_id');

    }
    public function items()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}

