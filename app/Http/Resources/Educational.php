<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Educational extends Resource
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
            'name'       => $this->name,
            'content'    => html_entity_decode( stripslashes( $this->content ) ),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
