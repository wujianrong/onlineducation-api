<?php

namespace App\Http\Resources;

use App\Models\Guest;
use App\Models\Lesson;
use Illuminate\Http\Resources\Json\Resource;

class Discusse extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'is_better'  => $this->is_better,
            'guest'      => $this->guest->nickname,
            'avatar'     => $this->guest->picture,
            'is_vip'     => $this->vip_id ? true : false,
            'lesson'     => $this->lesson->name,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
