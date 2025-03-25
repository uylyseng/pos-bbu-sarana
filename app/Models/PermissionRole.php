<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PermissionRole extends Model
{
    use HasFactory;

    protected $table = 'permission_roles';

    static function InsertUpdateRecord($permission_ids, $role_id)
    {
        PermissionRole::where('role_id', '=' ,$role_id)->delete();
        foreach ($permission_ids as $permission_id)
        {
            $save = new PermissionRole;
            $save->permission_id = $permission_id;
            $save->role_id = $role_id;
            $save->save();
        }
    }

    static function getRolePermission($role_id)
    {
        return PermissionRole::where('role_id', '=' ,$role_id)->get();
    }

    static function getPermission($slug, $role_id){
        return PermissionRole::select('permission_roles.id')
            ->join('permissions', 'permissions.id', '=', 'permission_roles.permission_id')
            ->where('permission_roles.role_id', '=' ,$role_id)
            ->where('permissions.slug', '=' , $slug)
            ->count();
    }
}
