<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\DiscusseCollection;
use App\Models\Discusse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscusseController extends Controller
{
    /**
     * 列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $discusses = Discusse::recent( 'discusses', [ 'guest', 'lesson' ] );

        return response()->json( new DiscusseCollection( $discusses ) );
    }

    /**
     * 精选
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     */
    public function better( $id )
    {
        Discusse::updateCache( $id, [ 'is_better' => 1 ], 'discusses' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 精选
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     */
    public function betterSome( Request $request )
    {
        $discusse_ids = $request->get( 'discusse_ids' );
        Discusse::whereIn( 'id', $discusse_ids )->update( [ 'is_better' => 1 ] );
        clear_cache( 'discusses' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }


    /**
     * 取消精选
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     */
    public function unBetter( $id )
    {
        Discusse::updateCache( $id, [ 'is_better' => 0 ], 'discusses' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 取消精选
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     */
    public function unBetterSome( Request $request )
    {
        $discusse_ids = $request->get( 'discusse_ids' );
        Discusse::whereIn( 'id', $discusse_ids )->update( [ 'is_better' => 0 ] );
        clear_cache( 'discusses' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 删除
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( $id )
    {
        Discusse::deleteCache( $id, 'discusses' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 批量删除
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroySome( Request $request )
    {
        $discusse_ids = $request->get( 'discusse_ids' );

        Discusse::whereIn( 'id', $discusse_ids )->delete();
        clear_cache( 'discusses' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }
}
