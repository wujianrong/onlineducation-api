<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\Video;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Cache;

class Section extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        $is_learned = 0;//是否观看过
        $is_last_learned = 0;//最近观看过

        if( guest_user() ){
            /*判断章节是否学习过*/
            $lessons = guest_user()->lessons()->whereIn( 'status', [ 2, 3 ] )->get();
            foreach( $lessons as $lesson ) {
                if( $this->lesson->id == $lesson->id ){
                    $array = json_decode( stripslashes( $lesson->pivot->sections ), true );
                    $sections = $array ? $array : [];
                    $is_learned = in_array( $this->id, $sections ) ? 1 : 0;
                    $is_last_learned = $sections && $this->id == $sections[count( $sections ) - 1] ? 1 : 0;
                    break;
                } else{
                    continue;
                }
            }
        }

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'lesson_name'     => $this->lesson->name,
            'is_free'         => $this->is_free,
            'is_learned'      => $is_learned,
            'is_last_learned' => $is_last_learned,
            'play_times'      => $this->play_times,
            'video'           => new \App\Http\Resources\Video( $this->video ),
            'created_at'      => $this->created_at->toDateTimeString(),
        ];
    }
}
