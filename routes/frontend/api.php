<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

/*客户端*/
Route::group( [ 'prefix' => 'item' ], function () {

    Route::post( 'login', 'AuthController@login' );// 登陆
    Route::post( 'get_wechat_config', 'OrderController@getWxConfig' );//获取微信jssdk,签名
    Route::get( 'get_code', 'AuthController@getCode' );//获取code


    //登陆认证 middleware 'auth:api'
    Route::group( [ 'middleware' => [ 'auth:api' ] ], function () {
		Route::get( 'lesson/{lesson}/preview', 'LessonController@preview' );// 预览
        /*个人信息*/
        Route::group( [ 'prefix' => 'guest' ], function () {
            Route::get( 'profile', 'GuestController@profile' );// 个人中心
            Route::post( 'send_sms', 'GuestController@sendSms' );// 发送验证码
            Route::post( '{guest}/check_tel', 'GuestController@checkTel' );// 绑定手机
        } );
        /*课程管理*/
        Route::group( [ 'prefix' => 'lesson' ], function () {
            Route::get( 'index', 'LessonController@index' );// 首页数据
            Route::get( '{lesson}/edit', 'LessonController@edit' ); //课程信息
            Route::get( '{nav}/nav_lessons', 'LessonController@navLessons' );// 栏目课程列表
            Route::get( '{nav}/nav/{genre}/genre_lessons', 'LessonController@genreLessons' );//分类课程列表
            Route::get( '{lesson}/collect', 'LessonController@collect' );//收藏课程
            Route::get( '{lesson}/like', 'LessonController@like' );//点赞课程
            Route::get( 'pay_orders', 'LessonController@payOrders' );//购买课程列表
            Route::get( 'collect_lessons', 'LessonController@collectLessons' );// 收藏课程列表
            Route::get( 'learned_lessons', 'LessonController@learnedLessons' );//学习记录
            Route::get( 'search', 'LessonController@search' );//搜索

            /*课时管理*/
            Route::get( 'section/{section}', 'SectionController@edit' );// 获取信息
        } );
        /*栏目管理*/
        Route::group( [ 'prefix' => 'nav' ], function () {
            Route::get( 'lists', 'NavController@lists' );// 列表
        } );
        /*消息管理*/
        Route::group( [ 'prefix' => 'message' ], function () {
            Route::get( '/lists', 'MessageController@lists' );// 列表
            Route::get( '{message}', 'MessageController@show' );//查看
        } );
        /*评论*/
        Route::group( [ 'prefix' => 'discusse' ], function () {
            Route::get( '{lesson}/lesson_discusses', 'DiscusseController@lessonDiscusse' );//列表
            Route::post( '{lesson}', 'DiscusseController@store' );// 发表评论
        } );
        /*订单管理*/
        Route::group( [ 'prefix' => 'order' ], function () {
            Route::get( '{vip}/vip', 'OrderController@createVip' );//vip下单
            Route::get( '{lesson}/lesson', 'OrderController@createLesson' );//课程下单
            Route::get( 'check_status', 'OrderController@checkStatus' );//订单状态查询
        } );
        /*广告管理*/
        Route::group( [ 'prefix' => 'advert' ], function () {
            Route::get( 'lists', 'AdvertController@lists' );// 列表
        } );
        /*首页设置*/
        Route::group( [ 'prefix' => 'setting' ], function () {
            Route::get( '{setting}', 'SettingController@index' );
        } );
        /*分类管理*/
        Route::group( [ 'prefix' => 'genre' ], function () {
            Route::get( 'lists', 'GenreController@lists' );// 列表
        } );
        /*vip管理*/
        Route::group( [ 'prefix' => 'vip' ], function () {
            Route::get( 'lists', 'VipController@lists' );// 列表
        } );

        Route::post( 'staff_message', 'MessageController@staffMessage' );// 反馈建议

    } );
} );

