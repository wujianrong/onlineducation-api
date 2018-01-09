<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\Mobile\LessonListCollection;
use App\Http\Resources\Mobile\LessonCollection;
use App\Http\Resources\Mobile\NavCollection;
use App\Http\Resources\Mobile\NavLessonListCollection;
use App\Http\Resources\Mobile\OrderCollection;
use App\Models\Genre;
use App\Models\Lesson;
use App\Models\Nav;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LessonController extends Controller
{
    /**
     * 首页课程
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $setting = Setting::firstOrFail();
        $last_lessons = Cache::get( 'guest_last_lesson_list' );
        $lesson_names = Cache::get( 'guest_last_lesson_name' );
        if( !$last_lessons ){
            $lesson_datas = Lesson::where( 'status', 3 )->orderBy( 'created_at', 'desc' );

            if( $setting->index_type == 1 ){//最新更新
                $last_lessons = $lesson_datas->paginate( $setting->index_count );
            } elseif( $setting->index_type == 2 ){//自定义
                $last_lessons = $lesson_datas->where( 'is_top', 1 )->get();
            }

            $lesson_names = Lesson::where( 'status', 3 )->orderBy( 'created_at', 'desc' )->get()->pluck( 'name' )->toArray();

            Cache::tags( 'lessons' )->put( 'guest_last_lesson_list', $last_lessons, 21600 );
            Cache::tags( 'lessons' )->put( 'guest_last_lesson_name', $last_lessons, 21600 );
        }

        $navs = Nav::recent( 'navs' );

        return response()->json( [
            'last_lessons' => new NavLessonListCollection( $last_lessons ),
            'nav'          => new NavCollection( $navs ),
            'lesson_names' => $lesson_names,
        ] );
    }

    /**
     * @编辑
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
        $lesson = Lesson::getCache( $id, 'lessons', [ 'nav', 'genres', 'teacher', 'educational', 'sections.video.video_urls' ] );

        return response()->json( new \App\Http\Resources\Mobile\Lesson( $lesson ) );
    }

    /**
     * 栏目课程
     * @param $nav_id
     * @return LessonCollection
     */
    public function navLessons( Nav $nav )
    {

        $lessons = Cache::get( 'guest_nav_lesson_list' );
        if( !$lessons ){
            $lessons = Lesson::where( 'status', 3 )->orderBy( 'created_at', 'desc' )->where( 'nav_id', $nav->id )->paginate( 8 );
            if( $nav->order_type == 2 ){
                $lessons = Lesson::where( 'status', 3 )->orderBy( 'play_times', 'desc' )->where( 'nav_id', $nav->id )->paginate( 8 );
            }
            Cache::tags( 'lessons' )->put( 'guest_nav_lesson_list', $lessons, 21600 );
        }

        return new NavLessonListCollection( $lessons );
    }

    /**
     * 分类课程
     * @param $genre_id
     * @return LessonCollection
     */
    public function genreLessons( $nav_id, $genre_id )
    {
        $lessons = Cache::get( 'guest_genre_lesson_list' );
        if( !$lessons ){
            $lessons = Genre::getCache( $genre_id, 'genres', [ 'lessons' ] )->lessons()->where( 'nav_id', $nav_id )->where( 'status', 3 )->paginate( 8 );

            Cache::tags( 'lessons' )->put( 'guest_genre_lesson_list', $lessons, 21600 );
        }

        return new NavLessonListCollection( $lessons );
    }

    /**
     * 收藏课程
     * @param Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect( Lesson $lesson )
    {
        /*
         * 关系   0    1   2   3   4   5   6   7
         * 点赞   -            -   -       -
         * 收藏        -       -       -   -
         * 购买            -       -   -   -
         * 观看                                -
         * */
        $guest_lessons = guest_user()->lessons();
        $guest_lessons_ids = $guest_lessons->get()->pluck( 'id' )->toArray();

        if( in_array( $lesson->id, $guest_lessons_ids ) ){
            foreach( $guest_lessons->get() as $guest_lesson ) {
                if( $guest_lesson->id == $lesson->id ){
                    /*收藏*/
                    if( $guest_lesson->pivot->type == 0 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 3,'collect_date' => Carbon::now() ] );
                        return response()->json( [ 'status' => true, 'message' => '收藏成功' ] );
                    } elseif( $guest_lesson->pivot->type == 2 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 5,'collect_date' => Carbon::now() ] );
                        return response()->json( [ 'status' => true, 'message' => '收藏成功' ] );
                    } elseif( $guest_lesson->pivot->type == 4 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 6,'collect_date' => Carbon::now() ] );
                        return response()->json( [ 'status' => true, 'message' => '收藏成功' ] );
                    } elseif( $guest_lesson->pivot->type == 7 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 1,'collect_date' => Carbon::now() ] );
                        return response()->json( [ 'status' => true, 'message' => '收藏成功' ] );

                        /*取消收藏*/
                    } elseif( $guest_lesson->pivot->type == 1 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 7 ] );
                        return response()->json( [ 'status' => true, 'message' => '取消收藏' ] );
                    } elseif( $guest_lesson->pivot->type == 3 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 0 ] );
                        return response()->json( [ 'status' => true, 'message' => '取消收藏' ] );
                    } elseif( $guest_lesson->pivot->type == 5 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 2 ] );
                        return response()->json( [ 'status' => true, 'message' => '取消收藏' ] );
                    } elseif( $guest_lesson->pivot->type == 6 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 4 ] );
                        return response()->json( [ 'status' => true, 'message' => '取消收藏' ] );
                    }
                }
            }
        } else{
            /*收藏*/
            $guest_lessons->attach( [ $lesson->id => [ 'type' => 1,'collect_date' => Carbon::now() ] ] );
            return response()->json( [ 'status' => true, 'message' => '收藏成功' ] );
        }

        return response()->json( [ 'status' => false, 'message' => '操作失败' ] );
    }

    /**
     * 点赞
     * @param Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function like( Lesson $lesson )
    {
        /*
         * 关系   0    1   2   3   4   5   6   7
         * 点赞   -            -   -       -
         * 收藏        -       -       -   -
         * 购买            -       -   -   -
         * 观看                                -
         * */
        $guest_lessons = guest_user()->lessons();
        $guest_lessons_ids = $guest_lessons->get()->pluck( 'id' )->toArray();
        if( in_array( $lesson->id, $guest_lessons_ids ) ){
            foreach( $guest_lessons->get() as $guest_lesson ) {
                if( $guest_lesson->id == $lesson->id ){
                    if( $guest_lesson->pivot->type == 1 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 3 ] );
                        $lesson = Lesson::updateCache( $lesson->id, [ 'like' => $lesson->like + 1 ], 'lessons' );
                        return response()->json( [ 'status' => true, 'message' => '操作成功', 'like_count' => $lesson->like ] );
                    } elseif( $guest_lesson->pivot->type == 2 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 4 ] );
                        $lesson = Lesson::updateCache( $lesson->id, [ 'like' => $lesson->like + 1 ], 'lessons' );
                        return response()->json( [ 'status' => true, 'message' => '操作成功', 'like_count' => $lesson->like ] );
                    } elseif( $guest_lesson->pivot->type == 5 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 6 ] );
                        $lesson = Lesson::updateCache( $lesson->id, [ 'like' => $lesson->like + 1 ], 'lessons' );
                        return response()->json( [ 'status' => true, 'message' => '操作成功', 'like_count' => $lesson->like ] );
                    } elseif( $guest_lesson->pivot->type == 7 ){
                        $guest_lessons->updateExistingPivot( $lesson->id, [ 'type' => 0 ] );
                        $lesson = Lesson::updateCache( $lesson->id, [ 'like' => $lesson->like + 1 ], 'lessons' );
                        return response()->json( [ 'status' => true, 'message' => '操作成功', 'like_count' => $lesson->like ] );
                    } else{
                        $lesson = Lesson::updateCache( $lesson->id, [ 'like' => $lesson->like + 1 ], 'lessons' );
                        return response()->json( [ 'status' => true, 'message' => '操作成功', 'like_count' => $lesson->like ] );
                    }
                }
            }
        } else{
            /*点赞*/
            $guest_lessons->attach( [ $lesson->id => [ 'type' => 0 ] ] );
            $lesson = Lesson::updateCache( $lesson->id, [ 'like' => $lesson->like + 1 ], 'lessons' );
            return response()->json( [ 'status' => true, 'message' => '操作成功', 'like_count' => $lesson->like ] );
        }

        return response()->json( [ 'status' => false, 'message' => '操作失败' ] );
    }

    /**
     * 购买记录列表
     * @return LessonCollection
     */
    public function payOrders()
    {
        $orders = Cache::get( 'guest_pay_orders_list' );
        if( !$orders ){
            $orders = guest_user()->orders()->whereStatus( 1 )->orderBy( 'created_at', 'desc' )->paginate( 8 );

            Cache::tags( 'orders' )->put( 'guest_pay_orders_list', $orders, 21600 );
        }

        return new OrderCollection( $orders );
    }

    /**
     *   学习记录
     *
     * @return LessonCollection
     */
    public function learnedLessons()
    {
        $learned_lessons = Cache::get( 'guest_learned_lesson_list' );
        if( !$learned_lessons ){
            $learned_lessons = guest_user()->learned_lessons()->whereIn( 'status', [ 2, 3 ] )->paginate( 8 );

            Cache::tags( 'lessons' )->put( 'guest_learned_lesson_list', $learned_lessons, 21600 );
        }

        return new LessonListCollection( $learned_lessons );
    }

    /**
     * 收藏课程
     * @return LessonCollection
     */
    public function collectLessons()
    {
        $lessons = Cache::get( 'guest_collect_lesson_list' );
        if( !$lessons ){
            $lessons = guest_user()->collect_lessons()->whereIn( 'status', [ 2, 3 ] )->paginate( 8 );

            Cache::tags( 'lessons' )->put( 'guest_collect_lesson_list', $lessons, 21600 );
        }

        return new NavLessonListCollection( $lessons );
    }

    /**
     * 课程 模糊搜索 name
     * @param Request $request
     * @return LessonCollection
     */
    public function search()
    {
        $word = request( 'word' );
        $page = request( 'page' ) ? request( 'page' ) : 1;
        $pagesize = request( 'pagesize' ) ? request( 'pagesize' ) : 8;

        $lessons = Lesson::where( 'name', 'like', '%' . $word . '%' )
            ->where( 'status', 3 )
            ->orderBy( 'play_times', 'desc' )
            ->offset( ($page - 1) * $pagesize )
            ->limit( $pagesize )
            ->get();

        return new NavLessonListCollection( $lessons );
    }


    /**
     * 课程预览
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview( $id )
    {
        $lesson = Lesson::getCache( $id, 'lessons', [ 'nav', 'genres', 'teacher', 'educational' ] );

        return response()->json( new \App\Http\Resources\Lesson( $lesson ) );
    }

}
