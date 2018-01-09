<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Resources\SectionCollection;
use App\Jobs\SetVideoPlayTime;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Cache;

class SectionController extends Controller
{
    /**
     * 章节信息
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
        $section = Section::with( 'lesson' )->findOrFail( $id );

        /*记录课程观看记录*/
        $pay_lessons = guest_user()->pay_lessons()->whereIn( 'status', [ 2, 3 ] )->get();
        $guest_lessons = guest_user()->lessons()->whereIn( 'status', [ 2, 3 ] )->get();
        $lessons = Lesson::whereIn( 'status', [ 2, 3 ] )->get();

        // 课程类型 1：免费  2：付费 3：vip
        if( $guest_lessons->count() ){//收藏、点赞、购买
            foreach( $guest_lessons as $guest_lesson ) {
                if( $section->lesson->id == $guest_lesson->id ){
                    if( $guest_lesson->type == 1 ){//免费课程
                        $this->updateLearned( $id, $guest_lesson );
                    } elseif( $guest_lesson->type == 2 ){//精品课程
                        if( $section->is_free || in_array( $guest_lesson->id, $pay_lessons->pluck( 'id' )->toArray() ) ){
                            $this->updateLearned( $id, $guest_lesson );
                        } else{
                            return response()->json( [ 'status' => false, 'message' => '您没有观看此视频，如需观看请购买此课程' ] );
                        }
                    } elseif( $guest_lesson->type == 3 ){//vip课程
                        if( guest_user()->vip_id || $section->is_free ){//vip课程
                            $this->updateLearned( $id, $guest_lesson );
                        } else{
                            return response()->json( [ 'status' => false, 'message' => '您没有观看此视频，如需观看请开通vip' ] );
                        }
                    }
                }
            }
        }

        //课程类型 1：免费  2：付费 3：vip
        if( $lessons->count() ){
            foreach( $lessons as $lesson ) {
                if( !in_array( $lesson->id, $guest_lessons->pluck( 'id' )->toArray() ) ){//没有购买和收藏、点赞
                    if( in_array( $id, $lesson->sections->pluck( 'id' )->toArray() ) ){
                        if( $lesson->type == 1 ){//免费课程
                            $this->addLearned( $id, $lesson );
                        } elseif( $lesson->type == 2 ){//精品课程
                            if( $section->is_free ){
                                $this->addLearned( $id, $lesson );
                            } else{
                                return response()->json( [ 'status' => false, 'message' => '您没有观看此视频，如需观看请购买此课程' ] );
                            }
                        } elseif( $lesson->type == 3 ){//vip课程
                            if( guest_user()->vip_id || $section->is_free ){//vip课程
                                $this->addLearned( $id, $lesson );
                            } else{
                                return response()->json( [ 'status' => false, 'message' => '您没有观看此视频，如需观看请开通vip' ] );
                            }
                        }
                    }
                }
            }
        }

        return response()->json( [ 'status' => true, 'sections' => new \App\Http\Resources\Section( $section ) ] );
    }

    /**
     * 更新播放记录
     * @param $lesson
     */
    public function updateLearned( $id, $lesson )
    {
        $array = json_decode( stripslashes( $lesson->pivot->sections ), true );
        $sections = $array ? $array : [];
        if( !in_array( $id, $sections ) ){
            array_push( $sections, $id );
        }

        $data = [ 'sections' => addslashes( json_encode( $sections ) ), 'add_date' => Carbon::now() ];
        guest_user()->lessons()->updateExistingPivot( $lesson->id, $data );

        $this->SetVideoPlayTime( $id );
    }

    /**
     * 添加播放记录
     * @param $lesson
     */
    public function addLearned( $id, $lesson )
    {
        $this->setStudent( $lesson );//添加学员

        $data = [ $lesson->id => [ 'sections' => addslashes( json_encode( [ $id ] ) ), 'type' => 7, 'add_date' => Carbon::now() ] ];
        guest_user()->lessons()->attach( $data );

        $this->SetVideoPlayTime( $id );
    }

    /**
     * 添加学员
     * @param $lesson
     */
    private function setStudent( $lesson )
    {
        $student = $lesson->student;
//        if(!$lesson->pivot->add_date){
        Lesson::updateCache( $lesson->id, [ 'student' => $student + 1 ], 'lessons' );
//        }
    }

    /**
     * 记录播放次数
     * @param Video $video
     * @return \Illuminate\Http\JsonResponse
     */
//    private function setPlayTimes($id)
//    {
//        $section = Section::findOrFail($id);
//        $lesson = Lesson::getCache($section->lesson_id,'lessons');
//
//        /*队列方式处理*/
//        SetVideoPlayTime::dispatch($lesson,$section) ->onConnection('redis')->onQueue('set_lesson_paly_times');
//    }

    /*设置播放次数*/
    /**
     * @test
     * @param $section_id
     */
    private function SetVideoPlayTime( $section_id )
    {
        $section = Section::with( 'lesson' )->findOrFail( $section_id );
        $play_times = $section->play_times;

        $data_section = [
            'play_times' => $play_times + 1
        ];
        Section::updateCache( $section->id, $data_section, 'sections' );

        sleep( 1 );

        $data_lesson = [
            'play_times' => array_sum( $section->lesson->sections()->get()->pluck( 'play_times' )->toArray() )//所有视频播放次数总和
        ];
        Lesson::updateCache( $section->lesson->id, $data_lesson, 'sections' );

    }

}
