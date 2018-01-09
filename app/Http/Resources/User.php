<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
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

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'nickname'   => $this->nickname,
            'frozen'     => $this->frozen,
            'gender'     => $gender,
            'role'       => $this->roles,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
