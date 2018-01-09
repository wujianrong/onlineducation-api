<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class Nav extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        $order_key = $this->order_type == 1 ? 'updated_at' : 'play_times';
        $lessons = $this->lessons()->where( 'is_nav', 1 )->whereStatus( 3 )->orderBy( $order_key, 'desc' )->select( 'id', 'name', 'title', 'pictrue' )->get();

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'pictrue'    => $this->pictrue,
            'order_type' => $this->order_type,
            'lessons'    => $lessons,
        ];
    }
}
