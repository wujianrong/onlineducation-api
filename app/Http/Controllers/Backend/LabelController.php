<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\LabelCollection;
use App\Models\Label;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LabelController extends Controller
{

	/**
	 * 列表
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function lists()
    {
        $labels = Label::recent( 'labels' );

        return response()->json( new LabelCollection( $labels ) );
    }


	/**
	 * 创建
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store( Request $request )
    {
        Label::storeCache( $request->all(), 'labels' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

	/**
	 *	所有标签名称
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names()
	{
		$label_names = Cache::get( 'label_names' );
		if( !$label_names ){
			$label_names = Label::all()->pluck('name')->toArray();
			Cache::tags( 'labels' )->put( 'label_names', $label_names, 21600 );
		}
		return response()->json( $label_names );
	}

	/**
	 * 编辑
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit( $id )
    {
		$label_names = Cache::get( 'label_names' );
		if( !$label_names ){
			$label_names = Label::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'labels' )->put( 'label_names', $label_names, 21600 );
		}
        return response()->json( ['label' => new \App\Http\Resources\Label( Label::getCache( $id, 'labels', [ 'guests' ] ) ), 'label_names' => $label_names ]);
    }


	/**
	 * 更新
	 * @param         $id
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update( $id, Request $request )
    {
        Label::updateCache( $id, $request->all(), 'labels' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }


	/**
	 * 删除
	 * @param Label $label
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy( Label $label )
    {
        if( $label->guests->count() ){
            return response()->json( [ 'status' => false, 'message' => '不能删除有学员的标签' ], 201 );
        } else{
            Label::deleteCache( $label->id, 'labels', 'guests' );
            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        }
    }
}
