<?php

namespace App\Http\Resources\Mobile;

use App\Models\Order;
use App\Models\User;
use App\Models\Vip;
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

        $order = $this->vip_id ? $this->vip_orders()->orderBy( 'created_at', 'desc' )->firstOrFail() : null;

        return [
            'id'           => $this->id,
            'nickname'     => $this->nickname,
            'phone'        => $this->phone,
            'picture'      => $this->picture,
            'position'     => $this->position,
            'gender'       => $gender,
            'is_expire'    => $this->end && $this->end->timestamp < time() ? true : false,//是否过期
            'vip_name'     => $this->vip_id ? $this->vip->name : null,
            'vip_end_date' => $order ? $order->end->toDateString() : null,
            'created_at'   => $this->created_at->toDateTimeString(),
        ];

    }
}
