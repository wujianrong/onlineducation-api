<?php

namespace App\Http\Controllers\Frontend;

/*查看和设置微信菜单*/

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public $menu;

    /**
     * MenuController constructor.
     * @param $menu
     */
    public function __construct( Application $app )
    {
        $this->menu = $app->menu;
    }

    /**
     * @return \EasyWeChat\Menu\Menu
     */
    public function menu()
    {
        $buttons = [
            [
                "type" => "view",
                "name" => "全部课程",
                "url"  => "http://mobile.edu.elinkport.com/#/"
            ], [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "个人中心",
                        "url"  => "http://mobile.edu.elinkport.com/#/userall/user"
                    ],
                    [
                        "type" => "view",
                        "name" => "最近学习",
                        "url"  => "http://mobile.edu.elinkport.com/#/learning"
                    ],
                    [
                        "type" => "view",
                        "name" => "已购买课程",
                        "url"  => "http://mobile.edu.elinkport.com/#/userall/buyclass"
                    ],
                    [
                        "type" => "view",
                        "name" => "意见反馈",
                        "url"  => "http://mobile.edu.elinkport.com/#/writefeedback"
                    ],
                ],
            ],
        ];

        $return_msg = $this->menu->add( $buttons );

        if( $return_msg['errcode'] == 0 ){
            echo '设置菜单成功';
        }
    }

    public function all()
    {
        return $this->menu->all();
    }

}
