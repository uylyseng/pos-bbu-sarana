<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id', // Consistent naming
        'status',
        'gender',
        'profile', // Assuming this is for profile picture path
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Get a single user by ID
    public static function getSingle($id)
    {
        return self::find($id);
    }

    // Get all users with their role names
    public static function getRecord()
    {
        return self::select('users.*', 'roles.name as role_name')
            ->leftJoin('roles', 'roles.id', '=', 'users.roles_id')
            ->orderBy('users.id', 'desc')
            ->paginate(10); // Returns 10 records per page
    }

    // Delete a user by ID
    public static function deleteRecord($id)
    {
        $record = self::getSingle($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    // Relationship with Role model (optional but recommended)
    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }
}
