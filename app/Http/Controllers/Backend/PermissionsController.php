<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * 获取指定权限信息
     *
     * @param  Permission $permission
     * @return json
     */
    public function edit( $id )
    {
        return response()->json( [ 'permission' => Permission::getCache( $id, 'permission', [ 'roles' ] ) ] );
    }

    /**
     * 获取所有权限(不分页 用于创建角色时显示)
     *
     * @return mixed
     */
    public function allPermissions()
    {
        $permissions = Permission::allPermission();

        return response()->json( [ 'permissions' => $permissions ] );
    }

    /**
     * 创建权限
     *
     * @param  PermissionCreateRequest $request
     * @return json
     */
    public function store( Request $request )
    {
        Permission::storeCache( $request->all(), 'permissions' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 更新权限
     *
     * @param  Permission $permission
     * @param  PermissionUpdateRequest $request
     * @return json
     */
    public function update( $id, Request $request )
    {
        Permission::updateCache( $id, $request->all(), 'permissions' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 删除指定权限
     *
     * @param  Permission $permission
     * @return json
     */
    public function destroy( $id )
    {
        Permission::deleteCache( $id, 'permissions' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }
}
