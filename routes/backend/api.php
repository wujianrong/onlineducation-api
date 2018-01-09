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

use Illuminate\Http\Request;

/*服务端*/
Route::post( 'login', 'LoginController@login' );// 登陆
Route::post( 'token/refresh', 'LoginController@refresh' );// 登陆
Route::get( 'logout', 'LoginController@logout' );// 退出登陆

//登陆认证 middleware 'auth:api'
Route::group( [ 'prefix' => 'admin', 'middleware' => [ 'auth:api' ] ], function () {
    Route::get( 'user/me', function ( Request $request ) {
        return auth_user();
    } );//登陆账号信息
    /*菜单数据*/
    Route::group( [ 'prefix' => 'menu' ], function () {
        Route::get( 'lists', 'MenuController@menus' );// 获取菜单
    } );

    //权限 middleware admin_auth
    Route::group( [ 'middleware' => [ 'admin_auth' ] ], function () {
        /*角色*/
        Route::group( [ 'prefix' => 'role' ], function () {
            Route::get( 'lists', 'RoleController@lists' )->name( 'role_lists' );// 角色列表
            Route::get( 'create', 'RoleController@create' )->name( 'role_create' );//所有权限
            Route::get( '{role}/edit', 'RoleController@edit' )->name( 'role_edit' );// 编辑
            Route::post( 'store', 'RoleController@store' ); // 创建角色
            Route::post( '{role}/update', 'RoleController@update' ); // 更新角色
            Route::get( '{role}/delete', 'RoleController@destroy' )->name( 'role_del' );// 删除角色
        } );
        /*权限*/
        Route::group( [ 'prefix' => 'permission' ], function () {
            Route::get( 'lists', 'PermissionsController@allPermissions' )->name( 'perm_lists' );// 获取所有权限
            Route::get( '{permission}', 'PermissionsController@edit' )->name( 'perm_edit' );//
            Route::post( 'create', 'PermissionsController@store' );// 权限
            Route::post( '{permission}/update', 'PermissionsController@update' );// 更新
            Route::get( '{permission}/delete', 'PermissionsController@destroy' )->name( 'perm_del' );   //删除
        } );
        /*教务设置*/
        Route::group( [ 'prefix' => 'education' ], function () {
            Route::get( 'lists', 'EducationController@lists' )->name( 'edu_lists' );//列表
            Route::get( '{educational}', 'EducationController@edit' )->name( 'edu_edit' );//获取
            Route::post( 'create', 'EducationController@store' );// 创建
            Route::post( '{educational}/update', 'EducationController@update' );// 更新
            Route::get( '{educational}/delete', 'EducationController@destroy' )->name( 'edu_del' );   //删除
        } );
        /*账号管理*/
        Route::group( [ 'prefix' => 'user' ], function () {
            Route::get( 'lists', 'UsersController@lists' )->name( 'user_lists' );// 账号列表
            Route::get( 'names', 'UsersController@names' );// 账号列表
            Route::post( 'store', 'UsersController@store' );// 创建账号
            Route::get( '{user}/edit', 'UsersController@edit' )->name( 'user_edit' );  // 获取指定账号的信息
            Route::get( '{user}/delete', 'UsersController@destroy' )->name( 'user_del' );  // 删除账号
            Route::get( '{user}/frozen', 'UsersController@frozen' )->name( 'user_frozen' );  // 冻结
            Route::get( '{user}/refrozen', 'UsersController@refrozen' )->name( 'user_refrozen' );  // 解冻
            Route::post( '{user}/update', 'UsersController@update' );// 更新账号
        } );
        /*系统日志*/
        Route::group( [ 'prefix' => 'log' ], function () {
            Route::get( 'lists', 'LogsController@lists' )->name( 'log_lists' );//列表
        } );
        /*用户管理*/
        Route::group( [ 'prefix' => 'guest' ], function () {
            Route::get( 'lists', 'GuestController@lists' )->name( 'guest_lists' );// 用户列表
            Route::post( '/', 'GuestController@store' );// 创建用户
            Route::get( '{guest}/frozen', 'GuestController@frozen' )->name( 'guest_frozen' );  // 获取指定用户的信息
            Route::get( '{guest}/refrozen', 'GuestController@refrozen' )->name( 'guest_refrozen' );  // 获取指定用户的信息
            Route::get( '{guest}', 'GuestController@edit' )->name( 'guest_edit' );  // 获取指定用户的信息
            Route::post( '{guest}/set_label', 'GuestController@setLabel' );  // 设置标签
            Route::get( '{guest}/delete', 'GuestController@destroy' )->name( 'guest_del' );  // 删除用户
            Route::post( '{guest}/update', 'GuestController@update' );// 更新
        } );
        /*视频管理*/
        Route::group( [ 'prefix' => 'video' ], function () {
            Route::get( 'lists', 'VideoController@lists' )->name( 'video_lists' );// 列表
            Route::get( 'names', 'VideoController@names' );// 列表
            Route::get( 'success_lists', 'VideoController@successList' );// 转码成功视频列表
            Route::post( '/', 'VideoController@store' );// 创建
            Route::get( '{video}', 'VideoController@edit' )->name( 'video_edit' );  // 获取指定的信息
            Route::get( '{video}/delete', 'VideoController@destroy' )->name( 'video_del' );  // 删除
            Route::post( '{video}/update', 'VideoController@update' );// 更新
        } );
        /*分类管理*/
        Route::group( [ 'prefix' => 'genre' ], function () {
            Route::get( 'lists', 'GenreController@lists' )->name( 'genre_lists' );// 列表
            Route::get( 'names', 'GenreController@names' );// 列表
            Route::post( '/', 'GenreController@store' );// 创建
            Route::get( '{genre}', 'GenreController@edit' )->name( 'genre_edit' );  // 获取指定的信息
            Route::get( '{genre}/delete', 'GenreController@destroy' )->name( 'genre_del' );  // 删除
            Route::post( '{genre}/update', 'GenreController@update' );// 更新
        } );
        /*栏目管理*/
        Route::group( [ 'prefix' => 'nav' ], function () {
            Route::get( 'lists', 'NavController@lists' )->name( 'nav_lists' );// 列表
            Route::get( 'names', 'NavController@names' );// 列表
            Route::post( '/', 'NavController@store' );// 创建
            Route::get( '{nav}', 'NavController@edit' )->name( 'nav_edit' );  // 获取指定的信息
            Route::get( '{nav}/delete', 'NavController@destroy' )->name( 'nav_del' );  // 删除
            Route::post( '{nav}/update', 'NavController@update' );// 更新
        } );
        /*课程管理*/
        Route::group( [ 'prefix' => 'lesson' ], function () {
            Route::get( 'lists', 'LessonController@lists' )->name( 'lesson_lists' );// 列表
            Route::get( 'names', 'LessonController@names' );// 列表
            Route::get( 'index_seting_list', 'LessonController@indexSetingList' );//首页设置课程列表
            Route::get( 'up_lesson_list', 'LessonController@upLessonList' );//上架课程列表（用图文和广告选择课程 ）
            Route::post( '/', 'LessonController@store' );// 创建
            Route::get( '{lesson}/up', 'LessonController@up' )->name( 'lesson_up' );//上架
            Route::get( '{lesson}/down', 'LessonController@down' )->name( 'lesson_down' );//下架
            Route::get( '{lesson}', 'LessonController@edit' )->name( 'lesson_edit' );  // 编辑
            Route::get( '{lesson}/delete', 'LessonController@destroy' )->name( 'lesson_del' );  // 删除
            Route::post( '{lesson}/update', 'LessonController@update' );// 更新
            /*学员管理*/
            Route::get( '{lesson}/student/lists', 'LessonController@students' )->name( 'lesson_student_lists' );// 列表

            /*课时管理*/
            Route::post( '{lesson}/section', 'SectionController@store' );// 创建
            Route::get( '/section/{section}/delete', 'SectionController@destroy' )->name( 'lesson_section_del' );  // 删除
            Route::post( '/section/{section}/update', 'SectionController@update' );// 更新
        } );
        /*用户标签*/
        Route::group( [ 'prefix' => 'label' ], function () {
            Route::get( 'lists', 'LabelController@lists' );// 列表
            Route::get( 'names', 'LabelController@names' );// 列表
            Route::post( '/', 'LabelController@store' );// 创建
            Route::get( '{label}', 'LabelController@edit' )->name( 'label_edit' );// 编辑
            Route::get( '{label}/delete', 'LabelController@destroy' )->name( 'label_del' );  // 删除
            Route::post( '{label}/update', 'LabelController@update' );// 更新
        } );
        /*评论*/
        Route::group( [ 'prefix' => 'discusse' ], function () {
            Route::get( 'lists', 'DiscusseController@lists' )->name( 'discusse_lists' );// 列表
            Route::get( '{discusse}/delete', 'DiscusseController@destroy' )->name( 'discusse_del' );  // 删除
            Route::get( '{discusse}/better', 'DiscusseController@better' )->name( 'discusse_better' );// 精选
            Route::get( '{discusse}/un_better', 'DiscusseController@unBetter' )->name( 'discusse_un_better' );// 取消精选
            Route::post( 'better_some', 'DiscusseController@betterSome' );// 批量精选
            Route::post( 'un_better_some', 'DiscusseController@unBetterSome' );// 批量取消精选
            Route::post( 'delete_some', 'DiscusseController@destroySome' );// 批量取消精选
        } );
        /*首页设置*/
        Route::group( [ 'prefix' => 'setting' ], function () {
            Route::get( '{setting}', 'SettingController@index' )->name( 'setting_index' );//数据
            Route::post( '{setting}/set_index_type', 'SettingController@setIndexType' );//设置
        } );
        /*讲师管理*/
        Route::group( [ 'prefix' => 'teacher' ], function () {
            Route::get( 'lists', 'TeacherController@lists' )->name( 'teacher_lists' );// 列表
            Route::get( 'names', 'TeacherController@names' );// 列表
            Route::post( '/', 'TeacherController@store' );// 创建
            Route::get( '{teacher}', 'TeacherController@edit' )->name( 'teacher_edit' );// 编辑
            Route::get( '{teacher}/delete', 'TeacherController@destroy' )->name( 'teacher_del' );  // 删除
            Route::post( '{teacher}/update', 'TeacherController@update' );// 更新
        } );
        /*广告管理*/
        Route::group( [ 'prefix' => 'advert' ], function () {
            Route::get( 'lists', 'AdvertController@lists' )->name( 'advert_lists' );// 列表
            Route::post( '/', 'AdvertController@store' );// 创建
            Route::get( '{advert}', 'AdvertController@edit' )->name( 'advert_edit' );// 编辑
            Route::get( '{advert}/delete', 'AdvertController@destroy' )->name( 'advert_del' );  // 删除
            Route::post( '{advert}/update', 'AdvertController@update' );// 更新
        } );
        /*消息管理*/
        Route::group( [ 'prefix' => 'message' ], function () {
            Route::get( 'lists', 'MessageController@lists' )->name( 'message_lists' );// 列表
            Route::get( 'sys_list', 'MessageController@sysLists' )->name( 'sys_lists' );//系统消息
            Route::post( 'send_messages', 'MessageController@sendMessages' );//群发
        } );
        /*vip管理*/
        Route::group( [ 'prefix' => 'vip' ], function () {
            Route::get( 'lists', 'VipController@lists' )->name( 'vip_lists' );// 列表
            Route::get( 'names', 'VipController@names' );// 列表
            Route::post( '/', 'VipController@store' );// 创建
            Route::get( '{vip}', 'VipController@edit' )->name( 'vip_edit' );// 编辑
            Route::get( '{vip}/up', 'VipController@up' )->name( 'vip_up' );// 上架
            Route::get( '{vip}/down', 'VipController@down' )->name( 'vip_down' );//下架
            Route::get( '{vip}/delete', 'VipController@destroy' )->name( 'vip_del' );  // 删除
            Route::post( '{vip}/update', 'VipController@update' );// 更新
            Route::post( '{vip}/set_up_time', 'VipController@setUpTime' );// 定时上架
            Route::post( '{vip}/set_down_time', 'VipController@setDownTime' );// 定时下架
        } );
        /*订单管理*/
        Route::group( [ 'prefix' => 'order' ], function () {
            Route::get( 'lists', 'OrderController@lists' )->name( 'order_lists' );// 列表
            Route::get( 'vip_order_list', 'OrderController@vipOrderList' )->name( 'vip_order_list' );// 列表
            Route::post( '/', 'OrderController@store' );// 创建
            Route::get( '{order}/delete', 'OrderController@destroy' )->name( 'order_del' );  // 删除
        } );
        /*图片管理*/
        Route::group( [ 'prefix' => 'image' ], function () {
            Route::post( 'upload', 'ImageController@upload' );// 列表
        } );
    } );
} );


