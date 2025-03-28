<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->hasMany(PermissionRole::class, 'role_id');
    }

    public static function getSingle($id)
    {
        return Role::find($id);
    }

    public static function getRecord()
    {
        return Role::withCount('permissions')->get();
    }
}
