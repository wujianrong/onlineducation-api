<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{

    /**
     *列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $orders = Order::recent( 'orders', [ 'guest' ] );

        return response()->json( new OrderCollection( $orders ) );
    }

    /**
     * @test
     * @return \Illuminate\Http\JsonResponse
     */
    public function vipOrderList()
    {
        $orders = Cache::get( 'vip_order_list' );
        if( !$orders ){
            $orders = Order::with( [ 'guest' ] )->whereStatus( 1 )->orderBy( 'created_at', 'desc' )->whereType( 2 )->get();
            Cache::tags( 'orders' )->put( 'vip_order_list', $orders, 21600 );
        }

        return response()->json( new OrderCollection( $orders ) );
    }

    /**
     *编辑
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
        return response()->json( new \App\Http\Resources\Order( Order::getCache( $id, 'orders', [ 'guest' ] ) ) );
    }


    /**
     *删除
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( $id )
    {
        Order::deleteCache( $id, 'orders' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }
}
