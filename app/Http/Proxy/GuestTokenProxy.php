<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 13:52
 */

namespace App\Http\Proxy;

use App\Models\AuthPassword;

class GuestTokenProxy
{
    protected $http;

    /**
     * TokenProxy constructor.
     *
     * @param $http
     */
    public function __construct( \GuzzleHttp\Client $http )
    {
        $this->http = $http;
    }

    /*====================== 移动端验证 ============================*/
    /**
     * 登陆
     * @param $name
     * @param $password
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login( $name, $password )
    {
        $auth_password = AuthPassword::where( 'name', $name )->firstOrFail();

        if( auth()->attempt( [ 'name' => $name, 'password' => $password ] ) ){

            $token = $auth_password->createToken( 'wechat_token' )->accessToken;

            return response()->json( [
                'status' => true,
                'token'  => $token
            ] );
        }

        return response()->json( [
            'status'  => false,
            'message' => '账号密码错误！'
        ], 421 );
    }

}