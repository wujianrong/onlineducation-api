<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class NavList extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray( $request )
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'pictrue'        => $this->pictrue,
            'order_type'     => $this->order_type,
            'lesson_names'   => $this->lessons()->where( 'is_nav', 1 )->whereStatus( 3 )->get()->pluck( 'name' )->toArray(),
            'lessons_is_nav' => $this->lessons()->where( 'is_nav', 1 )->whereStatus( 3 )->select( 'id', 'name', 'title', 'pictrue' )->get(),
            'lessons'        => $this->lessons()->where( 'is_nav', 0 )->whereStatus( 3 )->select( 'id', 'name', 'title', 'pictrue' )->get(),
            'created_at'     => $this->created_at->toDateTimeString(),
        ];

    }
}
