<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PermissionModel extends Model
{
    use HasFactory;


    protected $table = 'permissions';

    static public function getSingle($id)
    {
        return RoleModel::find($id);
    }

    static public function getRecord()
    {
        $getPermission = PermissionModel::groupBy('group_by')->get();
        $result = array();
        foreach ($getPermission as $value) {
            $getPermissionGroup = PermissionModel::getPermissionGroup($value->group_by);
            $data = array();
            $data['id'] = $value->id;
            $data['name'] = $value->name;
            $group = array();
            foreach($getPermissionGroup as $valueG)
            {
                $dataG = array();
                $dataG['id'] = $valueG->id;
                $dataG['name'] = $valueG->name;
                $group[] = $dataG;
            }
            $data['group'] = $group;
            $result[] = $data;
        }
        return $result;
    }

    static public function getPermissionGroup($group_by)
    {
        return PermissionModel::where('group_by',"=", $group_by)->get();
    }
}
