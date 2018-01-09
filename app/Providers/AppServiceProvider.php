<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Cron;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen( 'cron.collectJobs', function () {
            Cron::setDisablePreventOverlapping();
            Cron::setLaravelLogging( false );
            Cron::setLogOnlyErrorJobsToDatabase( true );//只记录错误日志
            Cron::add( 'send_vip_expire_msg', '30 9 * * *', function () {//每天早上9点半触发
                send_message();//vip到期提醒
            }, true );
            Cron::add( 'check_video_transCode_data', '* * * * *', function () {//每分钟触发
                pullEvent();//轮询视频上传回调
            }, true );
        } );


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
