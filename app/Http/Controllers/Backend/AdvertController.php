<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\AdvertCollection;
use App\Models\Advert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    protected $pathname = '/uploads/adverts/';


    /**
     * 列表数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $adverts = Advert::recent( 'adverts' );

        return response()->json( new AdvertCollection( $adverts ) );
    }

    /**
     * 编辑数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
        return response()->json( new \App\Http\Resources\Advert( Advert::getCache( $id, 'adverts' ) ) );
    }

    /**
     * 更新
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id, Request $request )
    {
        Advert::updateCache( $id, $request->all(), 'adverts' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {

        Advert::storeCache( $request->all(), 'adverts' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * @test
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( $id )
    {
        Advert::deleteCache( $id, 'adverts' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }
}
