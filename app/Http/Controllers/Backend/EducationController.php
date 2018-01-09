<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationalCollection;
use App\Models\Educational;
use Illuminate\Http\Request;

class EducationController extends Controller
{

    /*列表数据*/
    public function lists()
    {
        $educationals = Educational::recent( 'educationals' );

        return response()->json( new EducationalCollection( $educationals ) );

    }

    /*新建模版*/
    public function store( Request $request )
    {
        $name = $request->get( 'name' );
        $content = $request->get( 'content' );

        Educational::storeCache( [
            'name'    => $name,
            'content' => htmlentities( addslashes( $content ) ),
        ], 'educationals' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /*编辑*/
    public function edit( $id )
    {
        return response()->json( new \App\Http\Resources\Educational( Educational::getCache( $id, 'educationals' ) ) );
    }

    /*更新*/
    public function update( $id, Request $request )
    {
        $name = $request->get( 'name' );
        $content = $request->get( 'content' );

        Educational::updateCache( $id, [
            'name'    => $name,
            'content' => htmlentities( addslashes( $content ) ),
        ], 'educationals' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /*删除*/
    public function destroy( Educational $educational )
    {
        if( $educational->lessons ){
            return response()->json( [ 'status' => false, 'message' => '不能删除有课程的教务模版' ], 201 );
        } else{
            Educational::deleteCache( $educational->id, 'educationals' );

            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        }

    }
}
