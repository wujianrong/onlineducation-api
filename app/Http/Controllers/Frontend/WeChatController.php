<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AuthPassword;
use App\Models\Guest;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Log;
use EasyWeChat;
use Illuminate\Http\Request;

class WeChatController extends Controller
{

    public $server;
    public $user;

    /**
     * WeChatController constructor.
     * @param $app
     */
    public function __construct()
    {
        $this->server = EasyWeChat::server();
        $this->user = EasyWeChat::user();
    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info( 'request start.' ); // 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $this->server->setMessageHandler( function ( $message ) {
            //获取微信用户OPENID
            $openid = $message->FromUserName;

            $guest_counts = Guest::where( 'openid', $openid )->get()->count();//本地微信用户

            switch($message->MsgType) {
                case 'event':
                    if( $message->Event == 'subscribe' ){//微信关注
                        $wechat_sub = Setting::firstOrFail()->wechat_sub;
                        $subMessage = strip_tags( str_replace( '<br>', "\n", html_entity_decode( stripslashes( $wechat_sub ) ) ), "<a>" );
                        if( empty( $subMessage ) ){
                            $subMessage = '感谢您关注微课堂';
                        }

						$this->setGuest( $guest_counts,$openid); //储存用户信息

                        return $subMessage;
                    } elseif( $message->Event == 'unsubscribe' ){//取消关注
                        return '感谢您关注微课堂!';
                    }
                    break;
                case 'text'://文本回复
//                    return '感谢您关注微课堂!';
                    break;
                case 'image':
//                  return '感谢您关注微课堂!';
                    break;
                case 'voice':
//                  return '感谢您关注微课堂!';
                    break;
                case 'video':
//                  return '感谢您关注微课堂!';
                    break;
                case 'location':
//                  return '感谢您关注微课堂!';
                    break;
                case 'link':
//                  return '感谢您关注微课堂!';
                    break;
                // ... 其它消息
                default:
//                  return '感谢您关注微课堂!';
                    break;
            }


			$this->setGuest( true,$openid); //储存用户信息
        } );

        Log::info( 'request end.' );

        return $this->server->serve();
    }


    /**
     * 更新用户数据
     * @param $we_guest
     * @param $nickname
     * @param $headimgurl
     * @param $sex
     * @param $position
     */
    public function setGuest( $guest_counts,$openid )
    {
		//获取微信用户昵称
		$we_user = $this->user->get( $openid );

		$nickname = $we_user->nickname;  //获取微信用户昵称
		$headimgurl = $we_user->headimgurl;//头像
		$sex = $we_user->sex;//性别
		$position = $we_user->country . $we_user->province . $we_user->city;//位置

		$subscribe = $we_user->subscribe;//用户是否订阅该公众号标识

		if($subscribe){
			if( !$guest_counts ){//没有关注
				$auth_password = new AuthPassword();
				$auth_password->name = $nickname;
				$auth_password->password = bcrypt( $openid );
				$auth_password->save();

				$data = [
					'nickname'         => $nickname,
					'openid'           => $openid,
					'picture'          => $headimgurl,
					'gender'           => $sex,
					'position'         => $position,
					'auth_password_id' => $auth_password->id,
				];

				Guest::storeCache( $data, 'guests' );

			} else {
				$we_guest = Guest::where( 'openid', $openid )->firstOrFail();//本地微信用户

				if( $nickname && $we_guest->nickname !== $nickname ){
					Guest::updateCache( $we_guest->id, [ 'nickname' => $nickname ], 'guests' );
					AuthPassword::updateCache( $we_guest->auth_password, [ 'name' => $nickname ], 'auth_passwords' );
				} elseif( $headimgurl && $we_guest->picture !== $headimgurl ){
					Guest::updateCache( $we_guest->id, [ 'picture' => $headimgurl ], 'guests' );
				} elseif( $sex && $we_guest->gender !== $sex ){
					Guest::updateCache( $we_guest->id, [ 'gender' => $sex ], 'guests' );
				} elseif( $position && $we_guest->position !== $position ){
					Guest::updateCache( $we_guest->id, [ 'position' => $position ], 'guests' );
				}
			}
		}

    }

}
