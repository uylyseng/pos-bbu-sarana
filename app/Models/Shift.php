<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    // Allow mass assignment on these fields
    protected $fillable = [
        'user_id',
        'time_open',
        'time_close',
        'cash_in_hand',
        'total_cash',
        'cash_submitted',
    ];

    // A Shift belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   // In Shift model

public function payments() {
    return $this->hasMany(Payment::class);
}
}
