<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\GenreCollection;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GenreController extends Controller
{
    /*分类列表*/
    public function lists()
    {
        $genres = Cache::get( 'guest_genres_list' );
        if( !$genres ){
            $genres = Genre::select( 'id', 'name' )->get();
            Cache::tags( 'genres' )->put( 'guest_genres_list', $genres, 21600 );
        }

        return response()->json( $genres );
    }
}
