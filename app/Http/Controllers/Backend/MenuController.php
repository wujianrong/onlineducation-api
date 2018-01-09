<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class MenuController extends Controller
{
    /**
     * 获取菜单
     * @return mixed
     */
    public function menus()
    {

        $key = 'menus' . auth_user()->id;

        $menus = Cache::get( $key );
        if( !$menus ){
            $role = auth_user()->roles()->firstOrFail();
            $menus = $role->menus()->where( 'parent_id', 0 )->get();

            foreach( $menus as $menu ) {
                $menu->child = $role->menus()->where( 'parent_id', $menu->id )->get();
            }

            Cache::tags( Config::get( 'entrust.permission_role_table' ) )->forever( $key, $menus );

        }

        return response()->json( [ 'menus' => $menus ] );
//        $menus = app(MenuRepository::class)->getMenus(auth_user());

    }
}
