<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Guest;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\Vip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use EasyWeChat;
use EasyWeChat\Payment\Order as WeOrder;
use App\Http\Controllers\Controller;


class OrderController extends Controller
{
    public $payment;

    /**
     * WeChatController constructor.
     * @param $app
     */
    public function __construct()
    {
        $this->payment = EasyWeChat::payment();
    }

    /**
     * VIP订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createVip( Vip $vip )
    {
        $name = $vip->name;
        $guest_id = guest_user()->id;
        $order_no = $this->getOutTradeNo();
        $price = $vip->price;
        $pay_type = 1;
        $order_type_id = $vip->id;//订单对应课程或者vip的id
        $month = $vip->expiration;
        $title = $vip->describle;

        if( guest_user()->vip_id ){//如果续费
            $order = guest_user()->orders()->where( 'type', 2 )
                ->where( 'order_type_id', guest_user()->vip_id )
                ->orderBy( 'created_at', 'desc' )
                ->whereStatus( 1 )
                ->firstOrFail();
            $start = $order->end;
            $end = $order->end;

        } else{//第一次购买
            $start = Carbon::now();
            $end = Carbon::now();
        }

        $attributes = [
            'trade_type'   => 'JSAPI', // JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付，统一下单接口trade_type的传参可参考这里
            'body'         => $name, //商品描述
            'detail'       => '这是供应链微课堂的vip订单信息',//商品详情
            'out_trade_no' => $order_no,//商户订单号
            'total_fee'    => $price * 100,//订单金额,单位为分
            'notify_url'   => env( 'WECHAT_PAYMENT_NOTIFY_URL', 'your notify_url' ), // 支付结果通知网址，如果不设置则会使用配置里的默认地址，我就没有在这里配，因为在.env内已经配置了。
            'openid'       => guest_user()->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $data = [
            'name'          => $name,
            'price'         => $price,
            'order_no'      => $order_no,
            'type'          => 2,
            'guest_id'      => $guest_id,
            'mouth'         => $month,
            'pay_type'      => $pay_type,
            'end'           => $end->addMonth( $month ),
            'start'         => $start,
            'title'         => $title,
            'order_type_id' => $order_type_id,
        ];

        Order::storeCache( $data, 'orders' );

        $we_order = new WeOrder( $attributes );

        $result = $this->payment->prepare( $we_order );

        if( $result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS' ){

            $config = $this->payment->configForJSSDKPayment( $result->prepay_id );

            return response()->json( [ 'status' => true, 'config' => $config ] );
        } else{
            return response()->json( [ 'status' => false, 'message' => $result ] );
        }
    }

    /**
     * 课程订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createLesson( Lesson $lesson )
    {
        $name = $lesson->name;
        $guest_id = guest_user()->id;
        $order_no = $this->getOutTradeNo();
        $price = $lesson->price;
        $pay_type = 1;
        $order_type_id = $lesson->id;//订单对应课程或者vip的id
        $title = $lesson->title;
        $pictrue = $lesson->pictrue;


        $attributes = [
            'trade_type'   => 'JSAPI', // JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付，统一下单接口trade_type的传参可参考这里
            'body'         => $name, //商品描述
            'detail'       => '这是供应链微课堂的课程订单信息',//商品详情
            'out_trade_no' => $order_no,//商户订单号
            'total_fee'    => $price * 100,//订单金额,单位为分
            'notify_url'   => env( 'WECHAT_PAYMENT_NOTIFY_URL', 'your notify_url' ), // 支付结果通知网址，如果不设置则会使用配置里的默认地址，我就没有在这里配，因为在.env内已经配置了。
            'openid'       => guest_user()->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];

        $data = [
            'name'          => $name,
            'price'         => $price,
            'order_no'      => $order_no,
            'type'          => 1,
            'guest_id'      => $guest_id,
            'pay_type'      => $pay_type,
            'order_type_id' => $order_type_id,
            'title'         => $title,
            'pictrue'       => $pictrue,
        ];

        Order::storeCache( $data, 'orders' );

        $we_order = new WeOrder( $attributes );

        $result = $this->payment->prepare( $we_order );

        if( $result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS' ){

            $config = $this->payment->configForJSSDKPayment( $result->prepay_id );

            return response()->json( [ 'status' => true, 'config' => $config ] );
        } else{
            return response()->json( [ 'status' => false, 'message' => $result ] );
        }
    }


    /**
     * 微信jssdk配置签名
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWxConfig( Request $request )
    {

        $url = $request->get( 'url' );

        $http = new \GuzzleHttp\Client();
        $data = [
            'access_token' => EasyWeChat::access_token()->getToken(),
            'type'         => 'jsapi'
        ];

        $response = $http->request( 'GET', 'https://api.weixin.qq.com/cgi-bin/ticket/getticket', [
            'query' => $data
        ] );

        $token = json_decode( (string)$response->getBody(), true );

        $nonceStr = uniqid();
        $timeStamp = strval( time() );
        $params = [
            'appId'     => env( 'WECHAT_APPID' ),
            'timestamp' => $timeStamp,
            'nonceStr'  => $nonceStr,
            'signType'  => 'sha1',
        ];

        if( $token['errcode'] == 0 ){
            $ticket = $token['ticket'];
        } else{
            return response()->json( [ 'status' => false, 'config' => '支付签名失败' ] );
        }

        $srcStr = 'jsapi_ticket=' . $ticket . '&noncestr=' . $nonceStr . '&timestamp='
            . $timeStamp . '&url=' . $url;

        $params['paySign'] = sha1( $srcStr );

        return response()->json( [ 'status' => true, 'config' => $params ] );
    }

    //按时间生成22位订单号

    /**
     * @test
     * @return string
     */
    public function getOutTradeNo()
    {
        return date( 'YmdHis' ) . substr( implode( NULL, array_map( 'ord', str_split( substr( uniqid(), 7, 13 ), 1 ) ) ), 0, 8 );
    }


    /**
     * 订单回调处理
     * @return mixed
     */
    public function notifyUrl()
    {
        $response = $this->payment->handleNotify( function ( $notify, $successful ) {

            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::where( 'order_no', $notify->out_trade_no )->first();

            if( !$order ){ // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if( $order->paid_at ){ // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if( $successful ){
                // 不是已经支付状态则修改为已经支付状态, 更新支付时间为当前时间
                $order = Order::updateCache( $order->id, [
                    'pay_date' => time(),
                    'status'   => 1,
                ], 'orders' );

                if( $order->status == 1 ){//发送购买成功消息
                    if( $order->type == 1 ){//课程订单
                        /*
                         * 关系   0    1   2   3   4   5   6   7
                         * 点赞   -            -   -       -
                         * 收藏        -       -       -   -
                         * 购买            -       -   -   -
                         * 观看                                -
                         * */
                        $guest = Guest::getCache( $order->guest_id, 'guests' );
                        $guest_lessons = $guest->lessons()->get();
                        $guest_lessons_ids = $guest_lessons->pluck( 'id' )->toArray();
                        if( in_array( $order->order_type_id, $guest_lessons_ids ) ){
                            foreach( $guest_lessons as $guest_lesson ) {
                                if( $guest_lesson->id == $order->order_type_id ){
                                    if( $guest_lesson->pivot->type == 0 ){
                                        $guest->lessons()->updateExistingPivot( $order->order_type_id, [ 'type' => 4 ] );
                                    } elseif( $guest_lesson->pivot->type == 1 ){
                                        $guest->lessons()->updateExistingPivot( $order->order_type_id, [ 'type' => 5 ] );
                                    } elseif( $guest_lesson->pivot->type == 3 ){
                                        $guest->lessons()->updateExistingPivot( $order->order_type_id, [ 'type' => 6 ] );
                                    } elseif( $guest_lesson->pivot->type == 7 ){
                                        $guest->lessons()->updateExistingPivot( $order->order_type_id, [ 'type' => 2 ] );
                                    }
                                }
                            }
                        } else{
                            $guest->lessons()->attach( [ $order->order_type_id => [ 'type' => 2 ] ] );
                        }
                        send_pay_lesson_success_message( $order );
                    } elseif( $order->type == 2 ){//vip会员订单
                        Guest::updateCache( $order->guest_id, [
                            'vip_id' => $order->order_type_id
                        ], 'guests' );

                        $vip = Vip::sharedLock()->findOrFail( $order->order_type_id );
                        $vip_count = $vip->count;
                        Vip::updateCache( $order->order_type_id, [
                            'count' => $vip_count + 1
                        ], 'vips' );
                        send_pay_success_message( $order );
                    }
                }

            } else{ // 用户支付失败

                Order::updateCache( $order->id, [
                    'status' => 3,
                ], 'orders' );

            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        } );

        return $response;
    }
}
