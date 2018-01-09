<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\TeacherCollection;
use App\Models\Teacher;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    /**
     *列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $teachers = Teacher::recent( 'teachers', [ 'lessons' ] );

        return response()->json( new TeacherCollection( $teachers ) );
    }

	/**
	 *	所有讲师名称
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names()
	{
		$teacher_names = Cache::get( 'teacher_names' );
		if( !$teacher_names ){
			$teacher_names = Teacher::all()->pluck('name')->toArray();
			Cache::tags( 'teachers' )->put( 'teacher_names', $teacher_names, 21600 );
		}

		return response()->json( $teacher_names );
	}

    /**
     *编辑
     * @param Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
		$teacher_names = Cache::get( 'teacher_names' );
		if( !$teacher_names ){
			$teacher_names = Teacher::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'teachers' )->put( 'teacher_names', $teacher_names, 21600 );
		}

        return response()->json( ['teacher' => new \App\Http\Resources\Teacher( Teacher::getCache( $id, 'teachers', [ 'lessons' ] ) ),'teacher_names' => $teacher_names ]);
    }

    /**
     *更新
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id, Request $request )
    {
        Teacher::updateCache( $id, $request->all(), 'teachers' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     *创建
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        Teacher::storeCache( $request->all(), 'teachers' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     *删除
     * @param Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Teacher $teacher )
    {
        if( $teacher->lessons()->get()->count() ){
            return response()->json( [ 'status' => false, 'message' => '不能删除有课程关联的讲师' ], 201 );
        } else{
            Teacher::deleteCache( $teacher->id, 'teachers' );
            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        }
    }
}
