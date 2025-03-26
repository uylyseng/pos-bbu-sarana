<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Auth;

class RoleController extends Controller
{
    public function list()
    {
        $PermissionRole = PermissionRole::getPermission('roles', Auth::user()->roles_id);
        // dd($PermissionRole);

        if (empty($PermissionRole)) {
            abort(404);
        }

        $data['PermissionAdd'] = PermissionRole::getPermission('add-role', Auth::user()->roles_id);
        $data['PermissionEdit'] = PermissionRole::getPermission('edit-role', Auth::user()->roles_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('delete-role', Auth::user()->roles_id);

        $data['getRecord'] = Role::getRecord();
        return view('roles.list', $data);
    }

    public function create()
    {
        $PermissionRole = PermissionRole::getPermission('add-role', Auth::user()->roles_id);
        if(empty($PermissionRole))
        {
            abort(404);
        }

        $getPermission = Permission::getRecord();
        // dd($getPermission);
        $data['getPermission'] = $getPermission;
        return view('roles.add', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $PermissionRole = PermissionRole::getPermission('add-role', Auth::user()->roles_id);
        if(empty($PermissionRole))
        {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // dd($request->all());
        $save = new Role();
        $save->name = $request->name;
        $save->save();

        PermissionRole::InsertUpdateRecord($request->permission_id, $save->id);
        return redirect('roles')->with('success', 'Role added successfully.');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('edit-role', Auth::user()->roles_id);
        if(empty($PermissionRole))
        {
            abort(404);
        }


        $data['getRecord'] = Role::getSingle($id);
        $data['getPermission']   = Permission::getRecord();
        $data['getRolePermission'] = PermissionRole::getRolePermission($id);
        // dd($data['getRolePermission']);
        return view('roles.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('update-role', Auth::user()->roles_id);
        if(empty($PermissionRole))
        {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $save = Role::getSingle($id);
        $save->name = $request->name;
        $save->save();

        PermissionRole::InsertUpdateRecord($request->permission_id, $save->id);
        return redirect('roles')->with('success', 'Role update successfully.');
    }

    public function destroy($id)
    {
        $PermissionRole = PermissionRole::getPermission('delete-role', Auth::user()->roles_id);
        if(empty($PermissionRole))
        {
            abort(404);
        }

        $delete = Role::getSingle($id);
        $delete->delete();
        return redirect('roles')->with('success', 'Role deleted successfully.');
    }

}
