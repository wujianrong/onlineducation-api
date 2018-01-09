<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        $perm = Route::currentRouteName();//当前路由权限

        //local 本地   test 测试  online 正式
        if( env( 'APP_ENV' ) == 'test' && auth_user() && $perm ){
            if( !auth_user()->can( $perm ) ){
                return response()->json( [ 'message' => '你没有操作此功能权限' ], 403 );
            }
        }

        return $next( $request );
    }
}
