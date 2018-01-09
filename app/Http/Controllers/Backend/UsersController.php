<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    /**
     * 用户列表
     *
     * @return json
     */
    public function lists()
    {
        $users = Cache::get( 'user_list' );
        if( !$users ){
            $users = User::with( [ 'roles' ] )->whereNotIn( 'id', [ auth_user()->id ] )->orderBy( 'created_at', 'desc' )->get();
            Cache::tags( 'users' )->put( 'user_list', $users, 21600 );
        }

        return response()->json( new UserCollection( $users ) );
    }

	/**
	 * 所有账号名称
	 *
	 * @return json
	 */
	public function names()
	{
		$user_names = Cache::get( 'user_names' );
		if( !$user_names ){
		$user_names = User::all()->pluck('name')->toArray();
			Cache::tags( 'users' )->put( 'user_names', $user_names, 21600 );
		}

		return response()->json( $user_names );
	}


    /**
     * 显示指定用户信息
     *
     * @param  User $user
     * @return json
     */
    public function edit( $id )
    {
		$user_names = Cache::get( 'user_names' );
		if( !$user_names ){
			$user_names = User::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'users' )->put( 'user_names', $user_names, 21600 );
		}

        return response()->json( ['user' => new \App\Http\Resources\User( User::getCache( $id, 'users', [ 'roles' ] ) ),'user_names' => $user_names ]  );
    }


    /**
     * 获取所有角色
     *
     * @param  User $user
     * @return json
     */
    public function create()
    {
        $roles = Role::all();
        return response()->json( [ 'roles' => $roles ] );
    }

    /**
     * 创建用户
     *
     * @param  UserCreateRequest $request
     * @return json
     */
    public function store( Request $request )
    {
        $data = $request->all();;
        if( empty( $data['password'] ) ){
            unset( $data['password'] );
        } else{
            $data['password'] = Hash::make( $data['password'] );
        }

        $user = User::storeCache( $data, 'users' );

        if( !empty( $data['role_id'] ) ){
            $user->roles()->sync( $data['role_id'] );
        }

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 更新指定用户
     *
     * @param  User $user
     * @param  UserUpdateRequest $request
     * @return json
     */
    public function update( User $user, Request $request )
    {
        // 管理员可以修改用户角色
        if( auth_user()->isSuperAdmin() ){
            $data = $request->all();
        } else{
            $data = $request->except( [ 'role_id' ] );
        }
        if( isset( $data['password'] ) ){
            $data['password'] = Hash::make( $data['password'] );
        }

        User::updateCache( $user->id, $data, 'users' );

        if( auth_user()->isSuperAdmin() ){
            if( !empty( $roleId = $request->get( 'role_id' ) ) ){
                $user->roles()->sync( $roleId );
            }
        }

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 删除指定用户
     *
     * @param  User $user
     * @return json
     */
    public function destroy( User $user )
    {
        $user->roles()->detach();

        User::deleteCache( $user->id, 'users' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 冻结指定用户
     *
     * @param  User $user
     * @return json
     */
    public function frozen( User $user )
    {
        $user->frozen();

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 冻结指定用户
     *
     * @param  User $user
     * @return json
     */
    public function refrozen( User $user )
    {
        $user->refrozen();

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

}
