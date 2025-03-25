<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Supplier;
use App\Models\PaymentMethod;
use App\Models\PurchaseItem;


class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
       'supplier_id',
        'reference',
        'purchase_date',
        'subtotal',
        'total', 
        'discount',
        'payment_method_id',
        'status',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
  
protected $casts = [

    // other casts

    'purchase_date' => 'datetime',

];
    /**
     * Optionally, you can define relationships.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    
}
