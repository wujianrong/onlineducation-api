<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\GuestCollection;
use App\Http\Resources\LessonListCollection;
use App\Models\Lesson;
use App\Models\Nav;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LessonController extends Controller
{
    /**
     * 列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {

        $lessons = Cache::get( 'lesson_list' );
        if( !$lessons ){
            $lessons = Lesson::with( [ 'nav', 'genres' ] )->orderBy( 'created_at', 'desc' )->select( 'id', 'name', 'title', 'status', 'type', 'nav_id', 'price', 'created_at' )->get();
            Cache::tags( 'lessons' )->put( 'lesson_list', $lessons, 21600 );
        }

        $nav = Nav::recent( 'navs' );

        return response()->json( [
            'nav'     => $nav,
            'lessons' => new LessonListCollection( $lessons )
        ] );
    }

	/**
	 * 课程名称
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names()
	{
		$lesson_names = Cache::get( 'lesson_names' );
		if( !$lesson_names ){
			$lesson_names = Lesson::all()->pluck('name')->toArray();
			Cache::tags( 'lessons' )->put( 'lesson_names', $lesson_names, 21600 );
		}

		return response()->json( $lesson_names );
    }

    /**
     * 上架课程数据（用图文和广告选择课程 ）
     * @return \Illuminate\Http\JsonResponse
     */
    public function upLessonList()
    {

        $lessons = Cache::get( 'up_lesson_list' );
        if( !$lessons ){
            $lessons = Lesson::select( 'id', 'name', 'pictrue', 'updated_at' )->orderBy( 'created_at', 'desc' )->whereStatus( 3 )->get();
            Cache::tags( 'lessons' )->put( 'up_lesson_list', $lessons, 21600 );
        }

        return response()->json( $lessons );
    }

    /**
     * 首页设置课程数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexSetingList()
    {
        $lessons = Cache::get( 'index_lesson_list' );
        if( !$lessons ){
            $lessons = Lesson::select( 'id', 'name', 'is_top', 'pictrue' )->orderBy( 'created_at', 'desc' )->where( 'is_top', 0 )->whereStatus( 3 )->get();
            Cache::tags( 'lessons' )->put( 'index_lesson_list', $lessons, 21600 );
        }

        $top_lessons = Cache::get( 'index_lesson_list' );
        if( !$top_lessons ){
            $top_lessons = Lesson::select( 'id', 'name', 'is_top', 'pictrue' )->orderBy( 'created_at', 'desc' )->where( 'is_top', 1 )->whereStatus( 3 )->get();
            Cache::tags( 'lessons' )->put( 'index_lesson_list', $top_lessons, 21600 );
        }

        return response()->json( [
            'top_lessons' => $top_lessons,
            'lessons'     => $lessons,
        ] );
    }

    /**
     * 新建
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        $data = [
            'name'           => $request->get( 'name' ),
            'title'          => $request->get( 'title' ),
            'type'           => $request->get( 'type' ),
            'status'         => $request->get( 'status' ),
            'pictrue'        => $request->get( 'pictrue' ),
            'price'          => $request->get( 'price' ),
            'for'            => $request->get( 'for' ),
            'learning'       => implode( '--', $request->get( 'learning' ) ),
            'describle'      => $request->get( 'describle' ),
            'educational_id' => $request->get( 'educational_id' ),
            'nav_id'         => $request->get( 'nav_id' ),
            'teacher_id'     => $request->get( 'teacher_id' ),
        ];

        $lesson = Lesson::storeCache( $data, 'lessons' );
        $genre_ids = $request->get( 'genre_ids' );
        $lesson->genres()->sync( $genre_ids );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /**
     * @编辑
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
		$lesson_names = Cache::get( 'lesson_names' );
		if( !$lesson_names ){
			$lesson_names = Lesson::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'lessons' )->put( 'lesson_names', $lesson_names, 21600 );
		}

        $lesson = Lesson::getCache( $id, 'lessons', [ 'nav', 'genres', 'teacher', 'educational', 'sections.video.video_urls' ] );

        return response()->json( [ 'lesson' => new \App\Http\Resources\Lesson( $lesson ) ,'lesson_names' => $lesson_names ]  );
    }

    /** 更新 */
    public function update( Lesson $lesson, Request $request )
    {
        $data = [
            'name'           => $request->get( 'name' ),
            'title'          => $request->get( 'title' ),
            'type'           => $request->get( 'type' ),
            'pictrue'        => $request->get( 'pictrue' ),
            'price'          => $request->get( 'price' ),
            'for'            => $request->get( 'for' ),
            'learning'       => implode( '--', $request->get( 'learning' ) ),
            'describle'      => $request->get( 'describle' ),
            'educational_id' => $request->get( 'educational_id' ),
            'nav_id'         => $request->get( 'nav_id' ),
            'teacher_id'     => $request->get( 'teacher_id' ),
        ];

        Lesson::updateCache( $lesson->id, $data, 'lessons' );
        $genre_ids = $request->get( 'genre_ids' );

        /*更新课程章节*/
        $section_ids = $request->get( 'section_ids' );
        if( count( $section_ids ) ){
            Section::whereIn( 'id', $section_ids )->update( [ 'lesson_id' => $lesson->id ] );
            clear_cache( 'sections' );
        }

        /*更新课程免费章节*/
        $is_frees = $request->get( 'is_frees' );
        if( count( $is_frees ) ){
            Section::whereIn( 'id', $is_frees )->update( [ 'is_free' => 1 ] );
            clear_cache( 'sections' );
        }

        if( count( $genre_ids ) ){
            $lesson->genres()->sync( $genre_ids );
        }

        if( env( 'APP_ENV' ) == 'test' ){
            send_lesson_up_message( $lesson );//课程更新提醒
        }

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /** 删除 */
    public function destroy( Lesson $lesson )
    {
        if( $lesson->is_nav ){
            return response()->json( [ 'status' => false, 'message' => '不能删除推荐课程' ], 201 );
        } else{
            if( $lesson->status == 3 ){
                return response()->json( [ 'status' => false, 'message' => '不能删除上架中课程' ], 201 );
            } else{
                Lesson::deleteCache( $lesson->id, 'lessons', 'genres' );
                return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
            }
        }
    }

    /** 上架 */
    public function up( $id )
    {
        Lesson::updateCache( $id, [
            'status' => 3
        ], 'lessons' );

        return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
    }

    /** 下架 */
    public function down( Lesson $lesson )
    {
        if( !$lesson->is_nav ){
            Lesson::updateCache( $lesson->id, [
                'status' => 2
            ], 'lessons' );

            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        } else{
            return response()->json( [ 'status' => false, 'message' => '不能下架推荐课程' ], 201 );
        }

    }

    /**
     * 学员列表
     * @param Lesson $lesson
     * @return GuestCollection
     */
    public function students( Lesson $lesson )
    {
        $students = $lesson->students()->select( 'nickname', 'picture', 'gender', 'phone' )->get();

        return $this->getlearnedProcess( $students, $lesson );
    }

    /**
     * 获取学员学员学习进度和加入课程时间
     * @param $guests
     * @param $lesson
     */
    private function getlearnedProcess( $guests, $lesson )
    {
        foreach( $guests as $guest ) {
            $sections = json_decode( stripslashes( $guest->pivot->sections ), true );
            $lesson_sections = $lesson->sections->count();
            $guest->learned_per = number_format( count( $sections ) / $lesson_sections * 100, 2 ) . '%';//学习进度
            $guest->add_date = $guest->pivot->add_date ? $guest->pivot->add_date : null;//加入时间

            if( $guest->gender == 1 ){
                $gender = '男';
            } elseif( $guest->gender == 2 ){
                $gender = '女';
            } else{
                $gender = '未知';
            }

            $guest->gender = $gender;//性别
        }

        return $guests;
    }

}
