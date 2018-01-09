<?php


use Carbon\Carbon;
use Venturecraft\Revisionable\Revision;
use Illuminate\Database\Eloquent\Model;

if( !function_exists( 'array_swap' ) ){
    function array_swap( &$array, $i, $j )
    {
        if( $i != $j && array_key_exists( $i, $array ) && array_key_exists( $j, $array ) ){
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
        return $array;
    }
}

/*认证用户*/
if( !function_exists( 'auth_user' ) ){
    function auth_user()
    {
//        $key = 'auth_user_'.auth()->guard('api')->user()->id;
//        $auth_user = Cache::get($key);
//        if($auth_user){
//            return $auth_user;
//        }else{
        $auth_user = auth()->guard( 'api' )->user()->user;
//            Cache::put($key, $auth_user, 21600);
//        }

        return $auth_user;
    }
}

/*手机认证用户*/
if( !function_exists( 'guest_user' ) ){
    function guest_user()
    {
//        $key = 'guest_user_'.auth()->guard('api')->user()->id;
//        $guest_user = Cache::get($key);
//        if($guest_user){
//            return $guest_user;
//        }else{
        $guest_user = auth()->guard( 'api' )->user()->guest;
//            Cache::put($key, $guest_user, 21600);
//        }

        return $guest_user;
    }
}

/*手机认证用户*/
if( !function_exists( 'clear_cache' ) ){
    function clear_cache( $cache_key )
    {
        Cache::tags( $cache_key )->flush();
        Cache::tags( 'revisions' )->flush();
    }
}

/*vip到期提醒*/
if( !function_exists( 'send_message' ) ){
    function send_message()
    {

        $vip_expire_set = \App\Models\Setting::findOrFail( 1 )->vip_send_seting;
        $guests = \App\Models\Guest::recent( 'guests' )
            ->where( 'vip_id', '!=', null )
            ->where( 'frozen', 0 )
            ->get();

        foreach( $guests as $guest ) {

            $orders = $guest->orders()
                ->whereType( 2 )
                ->orderBy( 'end', 'desc' )
                ->whereStatus( 1 )
                ->where( 'order_type_id', $guest->vip_id );

            if( $orders->get()->count() ){
                $order = $orders->firstOrFail();
                $end = $order->end->toDateString();
                $order_data_first = Carbon::now()->addDays( $vip_expire_set )->toDateString();
                $order_data_second = Carbon::now()->addDays( $vip_expire_set - 2 )->toDateString();
                $order_data_three = $vip_expire_set - 4 >= 1 ? Carbon::now()->addDays( $vip_expire_set - 4 )->toDateString() : null;
                \Log::info( '会员到订单:' . $order->id . '----------end:' . $order->end->toDateString() . '-------------' . \Cron::getRunInterval() );
                if( $end == $order_data_first || $end == $order_data_second || $end == $order_data_three ){
                    $title = '您好，您的会员即将到期，请您注意。';
                    $expDate = $order->end->year . '年' . $order->end->month . '月' . $order->end->day . '日';
                    $data = [
                        'touser'      => $guest->openid,
                        'template_id' => 'Vd9hwT4VefpegQTl1gJD121pMCxZAijZ7ginJwhOJ7A',//会员到期提醒
                        'url'         => env( 'MOBILE_URL' ) . '/#/userall/myvip',
                        'data'        => [
                            "first"   => $title,
                            "name"    => '供应链微课堂' . $order->name,
                            "expDate" => $expDate,
                            "remark"  => "请及时续费会员，会员到期后，将无法观看VIP视频。",
                        ],
                    ];
                    $content = '<div>您的微课堂' . '供应链微课堂' . $order->name . '有效期至' . $expDate . '。备注：请及时续费会员，会员到期后，将无法观看VIP视频。</div>';

                    $result = \EasyWeChat::notice()->send( $data );
                    if( $result['errcode'] == 0 ){
                        store_template_message( [ $guest->id ], $title, $content );
                        \Log::info( '会员到期提醒成功---------' . $guest->nickname );
                    } else{
                        \Log::info( '会员到期提醒失败-----------' . $guest->nickname );
                    }
                }
            }
        }
    }
}

/*vip开通成功通知*/
if( !function_exists( 'send_vip_success_message' ) ){
    function send_pay_success_message( $order )
    {
        $guest = \App\Models\Guest::getCache( $order->guest_id, 'guests' );
        $expDate = $order->end->toDateTimeString();
        $title = '恭喜您已成功开通会员！';
        $data = [
            'touser'      => $guest->openid,
            'template_id' => 'APjSqvLbS4RXWbT3l-wxy3bxP8FteRnwQ4DKViH56Lo',//vip开通成功通知
            'url'         => '',
            'data'        => [
                "first"    => $title,
                "keyword1" => $order->name,
                "keyword2" => $guest->nickname,
                "keyword3" => $order->price . '元',
                "keyword4" => $expDate,
                "remark"   => "即日起，既可享受观看所有VIP专属视频和享受会员特权。",
            ],
        ];

        $content = '<div>会员名称：' . $order->name . '</div><div>会员账号：' . $guest->nickname . '</div><div>支付金额：' . $order->price . '元' .
            '</div><div>到期时间：' . $expDate . '</div><div>即日起，既可享受观看所有VIP专属视频和享受会员特权。</div>';

        $result = \EasyWeChat::notice()->send( $data );
        if( $result['errcode'] == 0 ){
            store_template_message( $guest->id, $title, $content );
            \Log::info( '发送vip开通成功通知成功' . $guest->nickname );
        } else{
            \Log::info( '发送vip开通成功通知失败' . $guest->nickname );
        }
    }
}

/*课程更新通知*/
if( !function_exists( 'send_lesson_up_message' ) ){
    function send_lesson_up_message( $lesson )
    {
        $guests = $lesson->guests()->wherePivotIn( 'type', [ 1, 2, 3, 4, 5, 6 ] )->get();//学习过的
        $guest_learneds = $lesson->guests()->wherePivot( 'sections', '!=', '' )->get();//学习过的

        if( $guests->count() ){
            foreach( $guests as $guest ) {
                $expDate = $lesson->updated_at->year . '年' . $lesson->updated_at->month . '月' . $lesson->updated_at->day . '日';
                $title = '你学习的' . $lesson->name . '课程又更新了，赶紧看看吧！';
                $data = [
                    'touser'      => $guest->openid,
                    'template_id' => '1ZJRqwDKfJptnAdDHhRzQwKtlOrmkSyN1tsUqaA0juI',//课程更新通知
                    'url'         => env( 'MOBILE_URL' ) . '/#/details/' . $lesson->id,
                    'data'        => [
                        "first"    => $title,
                        "keyword1" => $lesson->name,
                        "keyword2" => $expDate,
                        "remark"   => '',
                    ],
                ];
                $content = '<div>课程：' . $lesson->name . '</div><div>时间：' . $expDate . '</div>';

                $result = \EasyWeChat::notice()->send( $data );
                if( $result['errcode'] == 0 ){
                    store_template_message( $guest->id, $title, $content );
                    \Log::info( '发送课程更新通知成功' . $guest->nickname );
                } else{
                    \Log::info( '发送课程更新通知失败' . $guest->nickname );
                }
            }
        }

        if( $guest_learneds->count() ){
            foreach( $guest_learneds as $guest_learned ) {
                if( !in_array( $guest_learned->id, $guests->pluck( 'id' )->toArray() ) ){
                    $expDate = $lesson->updated_at->year . '年' . $lesson->updated_at->month . '月' . $lesson->updated_at->day . '日';
                    $title = '你学习的' . $lesson->name . '课程又更新了，赶紧看看吧！';
                    $data = [
                        'touser'      => $guest_learned->openid,
                        'template_id' => '1ZJRqwDKfJptnAdDHhRzQwKtlOrmkSyN1tsUqaA0juI',//课程更新通知
                        'url'         => env( 'MOBILE_URL' ) . '/#/details/' . $lesson->id,
                        'data'        => [
                            "first"    => $title,
                            "keyword1" => $lesson->name,
                            "keyword2" => $expDate,
                            "remark"   => '',
                        ],
                    ];
                    $content = '<div>课程：' . $lesson->name . '</div><div>时间：' . $expDate . '</div>';

                    $result = \EasyWeChat::notice()->send( $data );
                    if( $result['errcode'] == 0 ){
                        store_template_message( $guest_learned->id, $title, $content );
                        \Log::info( '发送课程更新通知成功' . $guest_learned->nickname );
                    } else{
                        \Log::info( '发送课程更新通知失败' . $guest_learned->nickname );
                    }
                }
            }
        }

    }
}

/*购买课程成功通知*/
if( !function_exists( 'send_pay_lesson_success_message' ) ){
    function send_pay_lesson_success_message( $order )
    {

        $guest = \App\Models\Guest::getCache( $order->guest_id, 'guests' );
        $expDate = $order->created_at->toDateTimeString();
        $title = '您已成功购买' . $order->name . '课程';
        $data = [
            'touser'      => $guest->openid,
            'template_id' => 'uO48b0NjfBAIgkoJBZ-3oZnz3pxLPdSjiJ16yYLP1PI',//购买课程成功通知
            'url'         => env( 'MOBILE_URL' ) . '/#/details/' . $order->order_type_id,
            'data'        => [
                "first"    => $title,
                "keyword1" => $order->order_no,
                "keyword2" => $order->name,
                "keyword3" => $order->price . '元',
                "keyword4" => $expDate,
                "remark"   => "感谢您的购买，祝你有所收获！",
            ],
        ];

        $content = '<div>订单编号：' . $order->order_no . '</div><div>课程名称：' . $order->name .
            '</div><div>订单金额：' . $order->price . '元' . '</div><div>购买时间：' . $expDate . '</div><div>感谢您的购买，祝你有所收获！</div>';

        $result = \EasyWeChat::notice()->send( $data );
        if( $result['errcode'] == 0 ){
            store_template_message( $guest->id, $title, $content );
            \Log::info( '发送购买课程成功通知成功' . $guest->nickname );
        } else{
            \Log::info( '发送购买课程成功通知失败' . $guest->nickname );
        }
    }
}

/*vip到期上架*/
if( !function_exists( 'up_vip_on_set_time' ) ){
    function up_vip_on_set_time()
    {
        $vips = \App\Models\Vip::recent( 'vips' )->where( 'up', '<=', \Carbon\Carbon::now()->timestamp )->get();
        foreach( $vips as $vip ) {
            \App\Models\Vip::updateCache( $vip->id, [ 'status' => 1 ], 'vips' );
        }
    }
}

/*vip到期下架*/
if( !function_exists( 'down_vip_on_set_time' ) ){
    function down_vip_on_set_time()
    {
        $vips = \App\Models\Vip::recent( 'vips' )->where( 'down', '<=', \Carbon\Carbon::now()->timestamp )->get();
        foreach( $vips as $vip ) {
            \App\Models\Vip::updateCache( $vip->id, [ 'status' => 3 ], 'vips' );
        }
    }
}

/*登陆日志*/
if( !function_exists( 'log_login' ) ){
    /*
     * $object 登陆用户
     * 登陆日志
    */
    function log_login( $object, $model )
    {
        $revision = new Revision();
        $revision->revisionable_type = $model;
        $revision->revisionable_id = $object->id;
        $revision->user_id = $object->id;
        $revision->key = 'login';
        $revision->new_value = Carbon::now();
        $revision->save();
    }
}

/*图片上传*/
if( !function_exists( 'upload' ) ){
    function upload( $request )
    {
        $file = $request->file( 'image' );
        //判断文件是否上传成功
        if( $file->isValid() ){
            //获取原文件名
            $originalName = $file->getClientOriginalName();
            //扩展名
            $ext = $file->getClientOriginalExtension();
            if( !in_array( $ext, [ 'png', 'gif', 'jpeg', 'jpg' ] ) ){
                return response()->json( [
                    'status'  => false,
                    'message' => '图片必须为png,gif,jpeg,jpg格式',
                ] );
            }
            //文件类型
            $type = $file->getClientMimeType();
            //临时绝对路径
            $realPath = $file->getRealPath();

            if( $file->getSize() > 1048576 ){
                return response()->json( [
                    'status'  => false,
                    'message' => '图片不能大于1M',
                ] );
            }
            $filename = 'nav_' . rtrim( $originalName, '.' . $ext ) . '-' . uniqid() . '.' . $ext;
            $bool = Storage::disk( 'images' )->put( $filename, file_get_contents( $realPath ) );
            if( $bool ){
                return response()->json( asset( 'storage/uploads/images/' . $filename ) );
            } else{
                return response()->json( [
                    'status'  => false,
                    'message' => '图片上传失败',
                ] );
            }
        } else{
            return response()->json( [
                'status'  => false,
                'message' => '图片上传失败',
            ] );
        }
    }
}


if( !function_exists( 'store_template_message' ) ){
    /*
     * $title 标题
     * $content 内容
     * $ids 消息接受者id
     * 存储模板消息
    */
    function store_template_message( $ids, $title, $content, $label = null, $user_id = null, $url = null, $picture = null )
    {
        $message = new \App\Models\Message();/*新建消息*/
        $message->title = $title;
        $message->content = htmlentities( addslashes( $content ) );
        $message->label = $label;
        $message->user_id = $user_id;
        $message->url = $url;
        $message->picture = $picture;
        $message->save();

        $message->guests()->attach( $ids );
    }
}


if( !function_exists( 'videoCommon' ) ){
    /**
     * 腾讯视频接口共用方法
     * @param $action
     * @param null $parms
     * @return mixed
     */
    function videoCommon( $action, $parms = null, $time = null )
    {
        if( !$time ){
            $time = Carbon::now()->timestamp;
        }
        $signaturemethod = 'HmacSHA256';
        $nonce = rand( 1, 9 ) * 10;

        /*获取腾讯接口签名，参数必须按字母排序*/
        $srcStr = 'GETvod.api.qcloud.com/v2/index.php?Action=' . $action . '&Nonce=' . $nonce . '&SecretId='
            . env( 'QCLOUD_SECRETID', null ) . '&SignatureMethod=' . $signaturemethod . '&Timestamp=' . $time;

        if( $parms ){
            foreach( $parms as $key => $parm ) {
                $srcStr .= '&' . $key . '=' . $parm;
            }
        }

        $signature = base64_encode( hash_hmac( 'sha256', $srcStr, env( 'QCLOUD_SECRETKEY', null ), true ) );

        $array_merge = [
            'Action'          => $action,
            'Nonce'           => $nonce,
            'SecretId'        => env( 'QCLOUD_SECRETID', null ),
            'Signature'       => $signature,
            'SignatureMethod' => $signaturemethod,
            'Timestamp'       => $time,
        ];

        if( $parms ){
            $array_merge = array_merge( [
                'Action'          => $action,
                'Nonce'           => $nonce,
                'SecretId'        => env( 'QCLOUD_SECRETID', null ),
                'Signature'       => $signature,
                'SignatureMethod' => $signaturemethod,
                'Timestamp'       => $time,
            ], $parms );
        }

        $http = new \GuzzleHttp\Client();
        $response = $http->request( 'GET', 'https://vod.api.qcloud.com/v2/index.php', [
            'query' => $array_merge
        ] );

        $result = json_decode( (string)$response->getBody(), true );

        return $result;

    }
}

if( !function_exists( 'pullEvent' ) ){
    /**
     *拉取事件通知
     */
    function pullEvent()
    {

//         Log::info(' pullEvent  ---- start ------'.Carbon::now()->toDateTimeString());

        $result = videoCommon( 'PullEvent', null, Carbon::now()->timestamp );

        if( $result['code'] == 0 && count( $result['eventList'] ) ){

//            Log::info(count($result['eventList']).' pullEvent  ---- count ------'.Carbon::now()->toDateTimeString());

            foreach( $result['eventList'] as $eventlist ) {

                $eventType = $eventlist['eventContent']['eventType'];
                $data = $eventlist['eventContent']['data'];
                $fileId = $data['fileId'];

                $video_datas = \App\Models\Video::where( 'fileId', $fileId );
                $video_data = null;

                if( $video_datas->count() ){
                    $video_data = \App\Models\Video::where( 'fileId', $fileId )->firstOrFail();

                    if( $eventType == 'ProcedureStateChanged' && $data['status'] == 'FINISH' ){

//                        Log::info('video_datas_countFINISH ----'.$fileId.'------'.Carbon::now()->toDateTimeString());

                        if( $data['errCode'] == 0 ){
                            //储存加密钥匙

                            $edk_key = $data['drm']['edkList'][0];
                            \App\Models\Video::updateCache( $video_data->id, [//加密转码成功
                                                                              'status'  => 2,
                                                                              'edk_key' => $edk_key
                            ], 'videos' );

                            $parms = [ 'fileId' => $fileId ];
                            $video = videoCommon( 'GetVideoInfo', $parms, Carbon::now()->timestamp );

                            //更新视频转码后的信息
                            if( $video['code'] == 0 ){
                                foreach( $video['transcodeInfo']['transcodeList'] as $index => $transImage ) {
                                    $size = round( $transImage['size'] / 1024 / 1024, 2 ) . 'M';

                                    /*格式化时长*/
                                    $hour = floor( $transImage['duration'] / 3600 );
                                    $minute = floor( ($transImage['duration'] - $hour * 3600) / 60 );
                                    $second = $transImage['duration'] - ($hour * 3600 + $minute * 60);

                                    $duration = $hour . '时' . $minute . '分' . $second . '秒';
                                    $url = $transImage['url'];
                                    $video_id = $video_data->id;

                                    $data_url = [
                                        'size'     => $size,
                                        'duration' => $duration,
                                        'url'      => $url,
                                        'video_id' => $video_id,
                                    ];

                                    \App\Models\VideoUrl::storeCache( $data_url, 'video_urls' );
                                }
                            }
                        } else{
                            \App\Models\Video::updateCache( $video_data->id, [ 'status' => 7 ], 'videos' );//加密转码失败
                        }

                        videoCommon( 'ConfirmEvent', [//确认事件通知
                                                      'msgHandle.0' => $eventlist['msgHandle'],
                        ], Carbon::now()->timestamp );

                    } elseif( $eventType == 'ProcedureStateChanged' && $data['status'] == 'FAIL' ){

//                        Log::info('video_datas_countFAIL ----'.$fileId.'------'.Carbon::now()->toDateTimeString());

                        videoCommon( 'ConfirmEvent', [//确认事件通知
                                                      'msgHandle.0' => $eventlist['msgHandle'],
                        ], Carbon::now()->timestamp );
                    }
                }
//                videoCommon('ConfirmEvent',[//确认事件通知
//                    'msgHandle.0' => $eventlist['msgHandle'],
//                ],Carbon::now()->timestamp);
                continue;
            }

        } else{
//            Log::info(' pullEvent  ----' .$result['message'] .'------'.Carbon::now()->toDateTimeString());
        }

    }
}








