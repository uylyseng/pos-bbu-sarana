<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    // Define the table name if it differs from the plural form of the model name
    protected $table = 'stores';

    // Define the fillable properties to allow mass assignment
    protected $fillable = [
        'name', 
        'image', 
        'contact', 
        'address', 
        'receipt_header', 
        'receipt_footer'
    ];
}