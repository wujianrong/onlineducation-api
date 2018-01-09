<?php

namespace App\Http\Resources;

use App\Models\Section;
use Illuminate\Http\Resources\Json\Resource;

class Video extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        if( $this->status == 4 ){
            $status = '转码中';
        } elseif( $this->status == 7 ){
            $status = '转码失败';
        } elseif( $this->status == 2 ){
            $status = '转码成功';
        } elseif( $this->status == 100 ){
            $status = '已删除';
        } elseif( $this->status == 6 ){
            $status = '删除中';
        } else{
            $status = '未知状态';
        }
        $url = $this->status == 2 ? optional( $this->video_urls()->orderBy( 'id', 'desc' )->firstOrFail() )->url : null;

//        if(auth_user()){
//            $token = auth_user()->auth_password->tokens()->where('revoked',0)->firstOrFail()->id;
//            $url =  str_replace('v.f240.m3u8','voddrm.token.'.$token.'.v.f240.m3u8', $url);
//        }
//
//        if(guest_user()){
//            $token = guest_user()->auth_password->tokens()->where('revoked',0)->firstOrFail()->id;
//            $url =  str_replace('v.f240.m3u8','voddrm.token.'.$token.'.v.f240.m3u8', $url);
//        }

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'status'     => $status,
            'url'        => $url,
            'size'       => $this->status == 2 ? optional( $this->video_urls()->firstOrFail() )->size : null,
            'duration'   => $this->status == 2 ? optional( $this->video_urls()->orderBy( 'id', 'desc' )->firstOrFail() )->duration : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
