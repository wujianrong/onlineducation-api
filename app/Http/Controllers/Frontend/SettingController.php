<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Lesson;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * 设置 数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( $id )
    {
        return new \App\Http\Resources\Setting( Setting::getCache( $id, 'settings' ) );
    }
}
