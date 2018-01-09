<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Message;
use Carbon\Carbon;
use EasyWeChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Qcloud\Sms\SmsSingleSender;
use \Illuminate\Http\Request;

class GuestController extends Controller
{

    /**
     * @var mixed
     */
    protected $appid;
    /**
     * @var mixed
     */
    protected $appkey;
    /**
     * @var mixed
     */
    protected $templId;
    /**
     * @var string
     */
    protected $cache_code;

    /**
     * GuestController constructor.
     * @param mixed $appid
     * @param mixed $appkey
     * @param mixed $templId
     */
    public function __construct()
    {
        $this->appid = env( 'QCLOUDSMS_APPID', null );
        $this->appkey = env( 'QCLOUDSMS_APPKEY', null );
        $this->templId = env( 'QCLOUDSMS_TEMPLID', null );
        $this->cache_code = 'sms_code';
    }

    /**
     * 显示指定用户信息
     *
     * @param  Guest $guest
     * @return
     */
    public function profile()
    {
        $messages_count = guest_user()->messages()->whereType( 0 )->get()->count();//未读消息

        return response()->json( [ 'guest' => new \App\Http\Resources\Mobile\Guest( guest_user() ), 'messages_count' => $messages_count ] );
    }

    /**
     * 发送验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSms( Request $request )
    {
        $code = $this->makeCode();
        $phoneNumber = $request->get( 'phone' );

        try {
            $sender = new SmsSingleSender( $this->appid, $this->appkey );
            $params = [ $code ];
            // 假设模板内容为：测试短信，{1}，{2}，{3}，上学。
            $result = $sender->sendWithParam( "86", $phoneNumber, $this->templId, $params, '' );

            $rsp = json_decode( $result, true );

            if( $rsp['result'] == 0 ){
                return response()->json( [ 'status' => true, 'message' => '发送成功' ] );
            } else{
                return response()->json( [ 'status' => $rsp, 'message' => '发送失败' ] );
            }

        } catch(\Exception $e) {
            return response()->json( [ 'status' => false, 'message' => $e ] );
        }
    }

    /**
     * 生成6为验码
     * @return string
     */
    public function makeCode()
    {
        //生成6位验证码
        $chars = '0123456789';
        mt_srand( (double)microtime() * 1000000 * getmypid() );
        $smscode = "";

        while(strlen( $smscode ) < 6)
            $smscode .= substr( $chars, (mt_rand() % strlen( $chars )), 1 );

        Cache::put( $this->cache_code, $smscode, 10 );

        return $smscode;
    }

    /**
     * 检测验证码，并绑定手机
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTel( Request $request, $id )
    {
        $code = $request->get( 'sms_code' );
        $phone = $request->get( 'phone' );

        if( Cache::get( $this->cache_code ) ){
            if( Cache::get( $this->cache_code ) == $code ){
                Guest::updateCache( $id, [
                    'phone' => $phone
                ], 'guests' );

                Cache::forget( $this->cache_code );//过去验证码

                return response()->json( [ 'status' => true, 'message' => '绑定成功' ] );
            } else{
                return response()->json( [ 'status' => false, 'message' => '验证码错误' ] );
            }
        } else{
            return response()->json( [ 'status' => false, 'message' => '验证码已过期' ] );
        }
    }

}
