<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\MessageCollection;
use App\Models\Label;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{

    /**
     * @var
     */
    protected $staff;

    /**
     * WeChatController constructor.
     * @param $app
     */
    public function __construct()
    {
        $this->staff = EasyWeChat::staff();
    }

    /**
     * 系统消息列表数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function sysLists()
    {
        $messages = Cache::get( 'sys_message_list' );
        if( !$messages ){
            $messages = Message::with( [ 'guests' ] )->orderBy( 'created_at', 'desc' )->where( 'user_id', null )->get();
            Cache::tags( 'messages' )->put( 'sys_message_list', $messages, 21600 );
        }

        return response()->json( new MessageCollection( $messages ) );
    }

    /**
     * 群发消息列表数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        $messages = Cache::get( 'message_list' );
        if( !$messages ){
            $messages = Message::with( [ 'guests' ] )->orderBy( 'created_at', 'desc' )->where( 'user_id', '!=', null )->get();
            Cache::tags( 'messages' )->put( 'message_list', $messages, 21600 );
        }

        return response()->json( new MessageCollection( $messages ) );
    }

    /**
     *
     * 直接到微信后台发送就可以满足需求
     * @param Request $request
     * @return string
     */
    public function sendMessages( Request $request )
    {
        $title = $request->get( 'title' );
        $content = $request->get( 'content' );
        $label_id = $request->get( 'label_id' );
        $url = $request->get( 'url' );
        $picture = $request->get( 'picture' );

        $label = Label::getCache( $label_id, 'labels' );
        if( strlen( $content ) < 6 ){
            return response()->json( [ 'status' => false, 'message' => '消息不能少于6个字符' ] );
        }

        $guests = $label->guests()->get();
        if( $guests->count() < 1 ){
            return response()->json( [ 'status' => false, 'message' => '请选择用户' ] );
        }

        $send_counts = 0;
        $error_names = '';//错误信息
        foreach( $guests as $guest ) {
            if( $url && $picture ){//图文消息
                $news = new EasyWeChat\Message\News( [
                    'title'   => $title,
                    'content' => $content,
                    'url'     => $url,
                    'image'   => $picture,
                ] );
                $reslut = $this->staff->message( $news )->to( $guest->openid )->send();
            } else{//文本消息
                $content = strip_tags( str_replace( '</div>', "\n", $content ), "<a>" );
                $text = new EasyWeChat\Message\Text( [ 'content' => $content ] );
                try{
					$reslut = $this->staff->message( $text )->to( $guest->openid )->send();
				}catch(\Exception $exception){
					report($exception);
					$error_names .= $guest->nickname.'、';

					continue;

				}

            }

            if( $reslut['errcode'] == 0 ){
                /*储存群发消息*/
                $send_counts++;
            }
        }

        if( $send_counts ){
            store_template_message( $guests, $title, $content, $label->name, auth_user()->id, $url, $picture );
        }

        if(empty($error_names)){
			$msg =  '发送成功' . $send_counts . '条消息';
		}else{
			$msg =  '发送成功' . $send_counts . '条消息,'.$error_names.'等用户发送失败';
		}

        return response()->json( [ 'status' => true, 'message' => $msg ] );

    }
}
