<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\VipCollection;
use App\Models\Vip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class VipController extends Controller
{
    /**
     *列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $vips = Vip::recent( 'vips', [ 'guests' ] );

        return response()->json( new VipCollection( $vips ) );
    }

	/**
	 *	所有vip名称
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names()
	{
		$vip_names = Cache::get( 'vip_names' );
		if( !$vip_names ){
			$vip_names = Vip::all()->pluck('name')->toArray();
			Cache::tags( 'vips' )->put( 'vip_names', $vip_names, 21600 );
		}


		return response()->json( $vip_names );
	}

    /**
     *创建
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        Vip::storeCache( $request->all(), 'vips' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     *编辑
     * @param Vip $vip
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
		$vip_names = Cache::get( 'vip_names' );
		if( !$vip_names ){
			$vip_names = Vip::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'vips' )->put( 'vip_names', $vip_names, 21600 );
		}

        return response()->json(['vip' => new \App\Http\Resources\Vip( Vip::getCache( $id, 'vips', [ 'guests' ] ) ),'vip_names' => $vip_names]);
    }

    /**
     *更新
     * @param Vip $vip
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id, Request $request )
    {
        Vip::updateCache( $id, $request->all(), 'vips' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }


    /**
     *  删除
     * @param Vip $vip
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Vip $vip )
    {
        if( $vip->guests->count() ){
            return response()->json( [ 'status' => false, 'message' => '不能删除有学员的vip' ], 201 );
        } else{
            Vip::deleteCache( $vip->id, 'vips' );
            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        }
    }

    /**
     *上架
     * @param Vip $vip
     * @return \Illuminate\Http\JsonResponse
     */
    public function up( $id )
    {
        Vip::updateCache( $id, [ 'status' => 1 ], 'vips' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }


    /**
     *下架
     * @param Vip $vip
     * @return \Illuminate\Http\JsonResponse
     */
    public function down( $id )
    {
        Vip::updateCache( $id, [ 'status' => 3 ], 'vips' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * @test
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUpTime( Request $request, $id )
    {
        $upTime = $request->get( 'up' );

        Vip::updateCache( $id, [ 'up' => $upTime ], 'vips' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * @test
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDownTime( Request $request, $id )
    {
        $downTime = $request->get( 'down' );

        Vip::updateCache( $id, [ 'down' => $downTime ], 'vips' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }


}
