<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'symbol', 'descript', 'conversion_rate'];

    // Defining one-to-many relationship with PurchaseItem.
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_unit_id');
    }

    // Optionally, you can define inverse relationships if you need them
    public function products()
    {
        return $this->hasMany(Product::class, 'sale_unit_id'); // Assuming you have a 'unit_id' column in the products table
    }
}
