<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\AdvertCollection;
use App\Models\Advert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    /**
     * 列表数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $adverts = Advert::recent( 'adverts' );

        return response()->json( new AdvertCollection( $adverts ) );
    }
}
