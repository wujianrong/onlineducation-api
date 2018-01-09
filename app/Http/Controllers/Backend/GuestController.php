<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuestCollection;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Http\Request;

class GuestController extends Controller
{


    /**
     * 用户列表
     *
     * @return
     */
    public function lists()
    {
        $guests = Guest::recent( 'guests', [ 'labels' ] );

        return response()->json( new GuestCollection( $guests ) );
    }

    /**
     * 显示指定用户信息
     *
     * @param  Guest $guest
     * @return
     */
    public function edit( $id )
    {
        return response()->json( new \App\Http\Resources\Guest( Guest::getCache( $id, 'guests', [ 'labels', 'discusses', 'vip' ] ) ) );
    }

    /**
     * 设置标签
     * @param Guest $guset
     * @param Request $request
     * @return mixed
     */
    public function setLabel( Guest $guest, Request $request )
    {
        $guest->setLabel( $request->get( 'label_ids' ) );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

}
