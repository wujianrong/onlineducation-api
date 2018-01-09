<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleCollection;
use App\Models\Permission;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * 获取所有角色(不分页 用于添加用户时显示 除了超级管理员角色)
     *
     * @return json
     */
    public function lists()
    {
        $roles = Role::recent( 'roles', [ 'perms' ] );

        return response()->json( new RoleCollection( $roles ) );
    }

    /**
     * 获取所有权限
     *
     * @param  Role $role
     * @return json
     */
    public function create()
    {
        $permissions = app( PermissionRepository::class )->getAllPermissions();

        return response()->json( [ 'permissions' => $permissions ] );
    }

    /**
     * 显示指定角色
     *
     * @param  Role $role
     * @return json
     */
    public function edit( $id )
    {
        $permissions = app( PermissionRepository::class )->getAllPermissions();

        return response()->json( [
            'role'  => new \App\Http\Resources\Role( Role::getCache( $id, 'roles' ) ),
            'perms' => $permissions
        ] );
    }

    /**
     * 创建角色
     *
     * @param  RoleCreateRequest $request
     * @return json
     */
    public function store( Request $request )
    {
        $data = $request->all();

        $role = Role::storeCache( $data, 'roles' );

        if( !empty( $data['permission_ids'] ) ){
            $permissionIds = app( Permission::class )->findOrfail( $data['permission_ids'] )->pluck( 'id' );
            $role->attachPermissions( $permissionIds );
        }

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 更新角色
     *
     * @param  Role $role
     * @param  RoleUpdateRequest $request
     * @return json
     */
    public function update( $id, Request $request )
    {
        $role = Role::updateCache( $id, $request->all(), 'roles' );

        $permissionIds = $request->get( 'permission_ids' );
        if( !empty( $permissionIds ) ){
            $permissionIds = app( Permission::class )->findOrfail( $permissionIds )->pluck( 'id' );
            $role->savePermissions( $permissionIds );
        }

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 删除角色
     *
     * @param  Role $role
     * @return json
     */
    public function destroy( Role $role )
    {
        if( $role->users->count() ){
            return response()->json( [ 'status' => false, 'message' => '不能删除有账号的角色' ], 201 );
        } else{

            $role->users()->detach();
            Role::deleteCache( $role->id, 'roles' );

            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        }
    }

}
