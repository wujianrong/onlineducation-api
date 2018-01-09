<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Vip extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        if( $this->status == 1 ){
            $status = '已上架';
        } elseif( $this->status == 2 ){
            $status = '未上架';
        } elseif( $this->status == 3 ){
            $status = '已下架';
        }

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'status'     => $status,
            'price'      => $this->price,
            'count'      => $this->count,
            'describle'  => $this->describle,
            'expiration' => $this->expiration,
            'up'         => $this->start ? $this->start->toDateTimeString() : null,
            'down'       => $this->end ? $this->end->toDateTimeString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
