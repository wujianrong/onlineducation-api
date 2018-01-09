<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Guest extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        if( $this->gender == 1 ){
            $gender = '男';
        } elseif( $this->gender == 2 ){
            $gender = '女';
        } else{
            $gender = '未知';
        }
        $labels = $this->labels->toArray();

        return [
            'id'         => $this->id,
            'nickname'   => $this->nickname,
            'phone'      => $this->phone,
            'picture'    => $this->picture,
            'gender'     => $gender,
            'labels'     => implode( '、', array_pluck( $labels, 'name' ) ),
            'label_ids'  => array_pluck( $labels, 'id' ),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
