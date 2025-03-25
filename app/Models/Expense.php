<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'expense_type_id',
        'payment_method_id',
        'reference',
        'expense_date',
        'amount',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Optionally, define relationships here.
     */
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
