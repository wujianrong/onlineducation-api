<?php

namespace App\Http\Resources\Mobile;

use App\Http\Resources\SectionCollection;
use App\Models\Educational;
use App\Models\Nav;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\Resource;

class Lesson extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        if( $this->type == 1 ){
            $type = '免费课程';
        } elseif( $this->type == 2 ){
            $type = '付费课程';
        } elseif( $this->type == 3 ){
            $type = 'VIP课程';
        }
        if( $this->status == 1 ){
            $status = '未上架';
        } elseif( $this->status == 2 ){
            $status = '已下架';
        } elseif( $this->status == 3 ){
            $status = '已上架';
        }
        $is_like = 0;//是否点赞
        $is_collect = 0;//是否收藏
        $is_show = 0;//对用户是否可见
        $is_learned = 0;//是否观看过

        $like_lessons_ids = guest_user()->like_lessons()->get()->pluck( 'id' )->toArray();
        $collect_lesson_ids = guest_user()->collect_lessons()->get()->pluck( 'id' )->toArray();
        $pay_lesson_ids = guest_user()->pay_lessons()->get()->pluck( 'id' )->toArray();
        if( in_array( $this->id, $like_lessons_ids ) ){//点赞课程
            $is_like = 1;
        }
        if( in_array( $this->id, $collect_lesson_ids ) ){//收藏课程
            $is_collect = 1;
        }
        //是否可以观看所有章节
        //  课程类型 1：免费  2：付费 3：vip
        if( $this->type == 1 ){//免费
            $is_show = 1;
        } elseif( $this->type == 2 && in_array( $this->id, $pay_lesson_ids ) ){//购买
            $is_show = 1;
        } elseif( guest_user()->vip_id && $this->type == 3 ){//vip
            $is_show = 1;
        }
        /*判断课程是否学习过*/
        $lessons = guest_user()->lessons()->whereIn( 'status', [ 2, 3 ] )->get();
        foreach( $lessons as $lesson ) {
            if( $lesson->id == $this->id ){
                $array = json_decode( stripslashes( $lesson->pivot->sections ), true );
                $is_learned = $array ? 1 : 0;
            }
        }

        $sections = $this->sections;
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'title'       => $this->title,
            'type'        => $type,
            'status'      => $status,
            'pictrue'     => $this->pictrue,
            'price'       => $this->price ? $this->price : $type,
            'like'        => $this->like,
            'is_like'     => $is_like,
            'is_collect'  => $is_collect,
            'is_show'     => $is_show,
            'is_learned'  => $is_learned,
            'play_times'  => $this->play_times,
            'for'         => $this->for,
            'learning'    => explode( '--', $this->learning ),
            'describle'   => $this->describle,
            'educational' => $this->educational_id ? new \App\Http\Resources\Educational( Educational::getCache( $this->educational_id, 'educationals' ) ) : null,
            'nav'         => Nav::getCache( $this->nav_id, 'navs' ),
            'teacher'     => $this->teacher_id ? Teacher::getCache( $this->teacher_id, 'teachers' ) : null,
            'genres'      => $this->genres,
            'sections'    => $sections ? new SectionCollection( $sections ) : null,
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}