<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Proxy\GuestTokenProxy;
use App\Models\AuthPassword;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use EasyWeChat;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    /**
     * @var GuestTokenProxy
     */
    protected $proxy;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( GuestTokenProxy $proxy )
    {
        $this->middleware( 'guest', [ 'except' => 'logout' ] );
        $this->proxy = $proxy;
    }

    /**
     *认证微信用户
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $guest = \App\Models\Guest::where( 'openid', request( 'openid' ) )->firstOrFail();

        if( $guest->frozen == 1 ){
            return response()->json( [ 'status' => false, 'message' => '该账号已被冻结' ] );
        }

        if( $guest ){
            return $this->proxy->login( $guest->nickname, request( 'openid' ) );
        } else{
            return response()->json( [ 'status' => false, 'message' => '请关注微课堂公众号' ] );
        }

    }


    /*
     * url_type
     * 1 全部课程 ,2个人中心 ,3最近学习,4已购买课程,5售后客服
     *
     * @param $url_type
     */
    public function auth( $url_name )
    {
        $user = session( 'wechat.oauth_user' ); // 拿到授权用户资料
        $url_name = str_replace( '_', '/', $url_name );
        if( $user ){
            if( $url_name === 'home' ){
                header( 'Location: ' . env( 'MOBILE_URL' ) . '#/?openid=' . $user->id );
            } else{
                header( 'Location: ' . env( 'MOBILE_URL' ) . '#/' . $url_name . '?openid=' . $user->id );
            }
        } else{
            header( 'Location: ' . env( 'MOBILE_URL' ) . '#/no_wechat' );
        }
    }

}
