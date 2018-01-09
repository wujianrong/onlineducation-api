<?php

/*微信框架入口*/
Route::any( '/wechat', 'WeChatController@serve' );

//设置微信菜单
Route::get( '/menu', 'MenuController@menu' );
//获取微信菜单数据
Route::get( '/menu/all', 'MenuController@all' );

/*微信认证用户获取*/
/*
 * url_type
 * 1 全部课程 ,2个人中心 ,3最近学习,4已购买课程,5售后客服
 *
 * */
Route::group( [ 'middleware' => [ 'wechat.oauth' ] ], function () {
    Route::get( '/mobile/{url_name}', 'AuthController@auth' );

} );

Route::post( '/order/notify_url', 'OrderController@notifyUrl' );//订单回调






