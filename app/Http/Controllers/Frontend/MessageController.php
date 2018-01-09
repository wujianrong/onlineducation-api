<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\Mobile\MessageCollection;
use App\Models\Guest;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{


    /**
     * 消息列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {

        $messages = Cache::get( 'guest_message_list' );
        if( !$messages ){
            $messages = guest_user()->messages()->orderBy( 'created_at', 'desc' )->paginate( 8 );

            Cache::tags( 'messages' )->put( 'guest_message_list', $messages, 21600 );
        }

        return new MessageCollection( $messages );
    }

    /**
     * 查看
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( $id )
    {

        $message = Message::getCache( $id, 'messages' );

        Message::updateCache( $id, [ 'type' => 1 ], 'messages' );//状态已读

        return new \App\Http\Resources\Mobile\Message( $message );
    }


    /**
     * 客服消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function staffMessage( Request $request )
    {
        $staff = EasyWeChat::staff();
        $content = $request->get( 'content' );

        $content = '【反馈建议】' . strip_tags( str_replace( '</div>', "\n", $content ), "<a>" ) . "\n ---反馈者:" . guest_user()->nickname;
        $text = new EasyWeChat\Message\Text( [ 'content' => $content ] );
        $reslut = $staff->message( $text )->to( env( 'STAFF_OPENID', 'oDMF40TXDO3tvmymHr5aMPM-1gu0' ) )->send();

        if( $reslut['errcode'] == 0 ){
            /*储存群发消息*/
            $guest_id = Guest::where( 'openid', env( 'STAFF_OPENID', 'oDMF40TXDO3tvmymHr5aMPM-1gu0' ) )->firstOrFail();
            store_template_message( optional( $guest_id )->id, '反馈建议消息', $content, null );
            return response()->json( [ 'status' => true, 'message' => '发送成功' ] );
        } else{
            return response()->json( [ 'status' => false, 'message' => '发送失败' ] );
        }
    }
}
