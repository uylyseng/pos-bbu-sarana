<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExchangeRate extends Model
{
    use HasFactory;

    protected $table = 'exchange_rates';
    protected $fillable = ['currency_from', 'currency_to', 'rate'];
    
    
    // Define relationships
    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_from');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_to');
    }
}
