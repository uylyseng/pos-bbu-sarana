<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'descript'];



public function orderItem()
{
    return $this->belongsTo(OrderItem::class);
}
public function productSize()
{
    return $this->belongsTo(ProductSize::class);
}
public function product()
{
    return $this->belongsTo(Product::class);
}
public function size()
{
    return $this->belongsTo(Size::class);
}

}
