<?php

namespace App\Http\Controllers\Frontend;

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
        $vips = Cache::get( 'guest_vip_list' );
        if( !$vips ){
            $vips = Vip::where( 'status', 1 )->orderBy( 'created_at', 'desc' )->get();

            Cache::tags( 'vips' )->put( 'guest_vip_list', $vips, 21600 );
        }

        return response()->json( new VipCollection( $vips ) );
    }

}
