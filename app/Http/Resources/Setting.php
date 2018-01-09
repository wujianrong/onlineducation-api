<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Setting extends Resource
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
            'id'              => $this->id,
            'index_type'      => $this->index_type,
            'index_count'     => $this->index_count,
            'vip_send_seting' => $this->vip_send_seting,
            'wechat_sub'      => html_entity_decode( stripslashes( $this->wechat_sub ) ),
            'created_at'      => $this->created_at->toDateTimeString(),
        ];
    }
}
