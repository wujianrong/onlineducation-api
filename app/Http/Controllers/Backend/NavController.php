<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\NavListCollection;
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
        $navs = Nav::recent( 'navs' );

        return response()->json( new NavListCollection( $navs ) );
    }

    /**
     * 栏目名称
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
		$nav_names = Cache::get( 'nav_names' );
		if( !$nav_names ){
			$nav_names = Nav::all()->pluck('name')->toArray();
			Cache::tags( 'navs' )->put( 'nav_names', $nav_names, 21600 );
		}

        return response()->json( $nav_names );
    }

    /**
     * 保存分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        Nav::storeCache( $request->all(), 'navs' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 显示数据
     * @param Nav $nav
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
		$nav_names = Cache::get( 'nav_names' );
		if( !$nav_names ){
			$nav_names = Nav::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'navs' )->put( 'nav_names', $nav_names, 21600 );
		}

        return response()->json( [ 'nav' => new \App\Http\Resources\NavList( Nav::getCache( $id, 'navs', [ 'lessons' ] ) ) ,'nav_names' => $nav_names ] );
    }

    /**
     * 更新数据
     * @param Nav $nav
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id, Request $request )
    {

        $lesson_ids = $request->get( 'lesson_ids' );

        $data = [
            'name'       => $request->get( 'name' ),
            'pictrue'    => $request->get( 'pictrue' ),
            'order_type' => $request->get( 'order_type' ),
        ];

        $nav = Nav::updateCache( $id, $data, 'navs' );
        $nav->lessons()->where( 'is_nav', 1 )->whereStatus( 3 )->update( [ 'is_nav' => 0 ] );
        $nav->lessons()->whereIn( 'id', $lesson_ids )->whereStatus( 3 )->update( [ 'is_nav' => 1 ] );
        clear_cache( 'navs' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 删除数据
     * @param Nav $nav
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Nav $nav )
    {
        if( !$nav->lessons->count() ){
            Nav::deleteCache( $nav->id, 'navs' );
            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        } else{
            return response()->json( [ 'status' => false, 'message' => '不能删除有课程的栏目' ], 201 );
        }
    }

}
