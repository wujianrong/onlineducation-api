<?php

namespace App\Http\Resources;

use App\Models\Educational;
use App\Models\Nav;
use App\Models\Teacher;
use App\Models\Video;
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
        $type = '免费课程';
        $status = '未上架';

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

        $students = $this->students()->get()->count();//学员数量

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'title'       => $this->title,
            'type'        => $type,
            'status'      => $status,
            'pictrue'     => $this->pictrue,
            'price'       => $this->price ? $this->price : $type,
            'is_top'      => $this->is_top,
            'is_nav'      => $this->is_nav,
			'is_show'     => 0,
			'is_like'     => 0,
			'is_collect'  => 0,
            'students'    => $students,
            'play_times'  => $this->play_times,
            'for'         => $this->for,
            'learning'    => explode( '--', $this->learning ),
            'describle'   => $this->describle,
            'educational' => $this->educational,
            'nav'         => $this->nav,
            'teacher'     => $this->teacher,
            'genres'      => $this->genres,
            'sections'    => new SectionCollection( $this->sections ),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];

    }
}
