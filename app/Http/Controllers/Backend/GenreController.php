<?php

namespace App\Http\Controllers\Backend;

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
        $genres = Genre::recent( 'genres' );

        return response()->json( new GenreCollection( $genres ) );
    }

    /*分类名称*/
    public function names()
    {
		$genre_names = Cache::get( 'genre_names' );
		if( !$genre_names ){
			$genre_names = Genre::all()->pluck('name')->toArray();
			Cache::tags( 'genres' )->put( 'genre_names', $genre_names, 21600 );
		}

        return response()->json($genre_names );
    }

    /*保存分类*/
    public function store( Request $request )
    {
        Genre::storeCache( $request->all(), 'genres' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /*显示数据*/
    public function edit( $id )
    {
		$genre_names = Cache::get( 'genre_names' );
		if( !$genre_names ){
			$genre_names = Genre::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'genres' )->put( 'genre_names', $genre_names, 21600 );
		}

        return response()->json( [ 'genre' => new \App\Http\Resources\Genre( Genre::getCache( $id, 'genres', [ 'lessons' ] ) ) , 'genre_names' => $genre_names ]  );
    }

    /*更新数据*/
    public function update( $id, Request $request )
    {
        Genre::updateCache( $id, $request->all(), 'genres' );
        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /*删除数据*/
    public function destroy( Genre $genre )
    {
        if( $genre->lessons->count() ){
            return response()->json( [ 'status' => false, 'message' => '不能删除有课程的分类' ], 201 );
        } else{

            Genre::deleteCache( $genre->id, 'genres', 'lessons' );
            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        }
    }

}
