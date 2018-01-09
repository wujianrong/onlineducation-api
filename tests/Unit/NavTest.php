<?php

namespace Tests\Unit;

use App\Models\Nav;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function store_a_nav()
    {
        $data = [
            'name' => '这是一个测试栏目',
            'pictrue' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=3374989003,48831532&fm=173&s=F02CBE5721E192A86184B4770300B068&w=550&h=309&img.JPEG',
            'order_type' => 1,
        ];

        $navs_count = Nav::all()->count();
        Nav::storeCache($data,'navs');
        $navs_after_store_count = Nav::all()->count();

        $this->assertEquals($navs_after_store_count,$navs_count+1);
    }

    /**
     * @test
     */
    public function update_a_nav()
    {
        $data = [
            'name' => '这是一个测试栏目',
            'pictrue' => 'https://ss1.baidu',
            'order_type' => 1,
        ];

         $nav = Nav::updateCache($this->nav->id,$data,'navs');

        $this->assertEquals('这是一个测试栏目',$nav->name);
        $this->assertEquals('https://ss1.baidu',$nav->pictrue);
        $this->assertEquals(1,$nav->order_type);
    }

    /**
     * @test
     */
    public function del_a_nav()
    {
        $navs_count = Nav::all()->count();
        
        Nav::deleteCache($this->nav->id,'navs');
        
        $navs_after_del_count = Nav::all()->count();

        $this->assertEquals($navs_after_del_count,$navs_count-1);
    }

}
