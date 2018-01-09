<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class NavLessonList extends Resource
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

        if( $this->type == 1 ){
            $type = '免费课程';
        } elseif( $this->type == 2 ){
            $type = '付费课程';
        } elseif( $this->type == 3 ){
            $type = 'VIP课程';
        }


        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'title'   => $this->title,
            'pictrue' => $this->pictrue,
            'price'   => $this->price ? $this->price : $type,
        ];
    }
}
