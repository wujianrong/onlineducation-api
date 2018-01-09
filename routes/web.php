<?php


Route::group( [ 'namespace' => 'Frontend' ], __DIR__ . '/frontend/web.php' );/*微信接口*/
Route::group( [ 'namespace' => 'Backend' ], __DIR__ . '/backend/web.php' );/*微信接口*/
Route::get( 'logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index' );

