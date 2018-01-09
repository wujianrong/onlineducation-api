<?php

namespace App\Http\Controllers\Backend;


use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SectionController extends Controller
{

    public function edit( $id )
    {
        $section = Section::getCache( $id, 'sections', [ 'video.video_urls' ] );

        return response()->json( new \App\Http\Resources\Section( $section ) );
    }

    /**
     * 新建
     * @param Lesson $lesson
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Lesson $lesson, Request $request )
    {
        Section::storeCache( [
            'name'      => $request->get( 'name' ),
            'is_free'   => $request->get( 'is_free' ),
            'video_id'  => $request->get( 'video_id' ),
            'lesson_id' => $lesson->id,
        ], 'sections' );

        if( env( 'APP_ENV' ) == 'test' ){
            send_lesson_up_message( $lesson );//课程更新提醒
        }

		Cache::forget('video_success_list');

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 更新
     * @param Section $section
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( Section $section, Request $request )
    {
        Section::updateCache( $section->id, $request->all(), 'sections' );

        if( env( 'APP_ENV' ) == 'test' ){
            send_lesson_up_message( $section->lesson );//课程更新提醒
        }

		Cache::forget('video_success_list');

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     *删除
     * @param Section $section
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Section $section )
    {
        Section::deleteCache( $section->id, 'sections' );

        if( env( 'APP_ENV' ) == 'test' ){
            send_lesson_up_message( $section->lesson );//课程更新提醒
        }

		Cache::forget('video_success_list');

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }


}
