<?php

namespace App\Http\Resources\Mobile;

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
        /*判断课程是否学习过*/
        $lesson = guest_user()->learned_lessons()->whereIn( 'status', [ 2, 3 ] )->findOrFail( $this->id );
        $learnd_section_ids = json_decode( stripslashes( $lesson->pivot->sections ), true );

        $last_section = $lesson->sections()->findOrFail( $learnd_section_ids[count( $learnd_section_ids ) - 1] )->name;

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'title'    => $this->title,
            'pictrue'  => $this->pictrue,
            'sections' => $last_section,
        ];
    }
}
