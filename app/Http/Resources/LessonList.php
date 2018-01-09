<?php

namespace App\Http\Resources;

use App\Models\Nav;
use Illuminate\Http\Resources\Json\Resource;

class LessonList extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
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
            'id'         => $this->id,
            'name'       => $this->name,
            'title'      => $this->title,
            'type'       => $type,
            'status'     => $status,
            'pictrue'    => $this->pictrue,
            'price'      => $this->price ? $this->price : $type,
            'students'   => $students,
            'nav'        => $this->nav,
            'genres'     => $this->genres,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
