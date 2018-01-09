<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Revision extends Resource
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
            'id'                => (int)$this->id,
            'revisionable_type' => $this->revisionable_type,
            'user'              => $this->user,
            'key'               => $this->key,
            'old_value'         => html_entity_decode( stripslashes( $this->old_value ) ),
            'new_value'         => html_entity_decode( stripslashes( $this->new_value ) ),
            'content'           => $this->content,
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }


}
