<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 18:52
 */

namespace App\Repositories;


use App\Models\Permission;
use App\Models\Role;

class PermissionRepository
{

    /**
     * 获取角色权限，和子权限
     * @param Role $role
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolePermissions( Role $role )
    {
        $permissions = $role->perms()->get();
        foreach( $permissions as $permission ) {
            if( $permission->parent_id == 0 ){
                $permission->subPermissions = app( Permission::class )->where( 'parent_id', $permission->id )->get();
            }
        }

        return $permissions;
    }

    /**
     *获取所有权限
     * @return mixed
     */
    public function getAllPermissions()
    {
        $permissions = app( Permission::class )->where( 'parent_id', 0 )->get();
        foreach( $permissions as $permission ) {
            $permission->subPermissions = app( Permission::class )->where( 'parent_id', $permission->id )->get();
        }

        return $permissions;
    }
}