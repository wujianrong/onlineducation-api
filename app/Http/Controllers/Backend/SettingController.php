<?php

namespace App\Http\Controllers\Backend;

use App\Models\Lesson;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * 设置 数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( $id )
    {
        return response()->json( new \App\Http\Resources\Setting( Setting::getCache( $id, 'settings' ) ) );
    }

    /**
     * 首页设置类型 1：最新更新 2：自定义
     * @param Setting $setting
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setIndexType( $id, Request $request )
    {

        $index_type = $request->get( 'index_type' );
        $index_count = $request->get( 'index_count' );
        $vip_send_seting = $request->get( 'vip_send_seting' );
        $wechat_sub = htmlentities( addslashes( $request->get( 'wechat_sub' ) ) );
        $wechat_sub = strip_tags( str_replace( '<div>', "\n", $wechat_sub ), "<a>" );

        $datas = [];
        $datas = $this->getData( 'index_type', $index_type, $datas );
        $datas = $this->getData( 'index_count', $index_count, $datas );
        $datas = $this->getData( 'vip_send_seting', $vip_send_seting, $datas );
        $datas = $this->getData( 'wechat_sub', $wechat_sub, $datas );

        if( $index_type == 2 ){

            /*还原课程置顶*/
            $lessons = Lesson::where( 'is_top', 1 );
            foreach( $lessons as $lesson ) {
                Lesson::updateCache( $lesson->id, [ 'is_top' => 0 ], 'lessons' );
            }

            /*置顶课程*/
            $lesson_ids = $request->get( 'lesson_ids' );
            foreach( $lesson_ids as $lesson_id ) {
                Lesson::updateCache( $lesson_id, [ 'is_top' => 1 ], 'lessons' );
            }
        }

        Setting::updateCache( $id, $datas, 'settings' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * 提交格式参数
     * @param $index
     * @param $index_type
     * @param $datas
     * @return mixed
     */
    public function getData( $index, $index_type, $datas )
    {
        if( $index_type ){
            $datas[$index] = $index_type;
        }

        return $datas;
    }

}
