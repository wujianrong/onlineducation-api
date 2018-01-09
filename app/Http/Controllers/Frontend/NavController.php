<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Nav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NavController extends Controller
{
    /**
     * 分类列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {

        $navs = Cache::get( 'guest_nav_list' );
        if( !$navs ){
            $navs = Nav::select( 'id', 'name' )->orderBy( 'created_at', 'desc' )->get();

            Cache::tags( 'navs' )->put( 'guest_nav_list', $navs, 21600 );
        }

        return response()->json( $navs );
    }

}
