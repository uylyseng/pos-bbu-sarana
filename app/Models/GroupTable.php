<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GroupTable extends Model
{
    use HasFactory;

    protected $table = 'group_tables'; // Define table name

    protected $fillable = ['name', 'descript']; // Allow mass assignment
    public $timestamps = false; // Disable timestamps
 
    public function tables()
    {
        // Specify 'group_id' as the foreign key if that's what your tables table uses.
        return $this->hasMany(Table::class, 'group_id');
    }
}
