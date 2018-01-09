<?php

namespace Tests\Unit;

use App\Models\Advert;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdvertTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function update_a_advert()
    {

        $data = [
            'name' => '修改广告',
            'path' =>'修改广告地址',
            'order' => 3,
            'type' => 1,
            'url' => '修改广告链接',
        ];


        $advert = Advert::updateCache($this->advert->id,$data,'adverts');

        $this->assertEquals('修改广告',$advert->name);
        $this->assertEquals('修改广告地址',$advert->path);
        $this->assertEquals(3,$advert->order);
        $this->assertEquals('修改广告链接',$advert->url);
        $this->assertEquals(1,$advert->type);
    }

    /**
     * @test
     */
    public function sotre_a_advert()
    {
        $adverts = Advert::all()->count();

        $data = [
            'name' => '修改广告',
            'path' =>'修改广告地址',
            'order' => 3,
            'type' => 1,
            'url' => '修改广告链接',
        ];

        Advert::storeCache($data,'adverts');

        $adverts_after_store = Advert::all()->count();
        $this->assertEquals($adverts_after_store,$adverts+1);
    }

    /**
     * @test
     */
    public function del_a_advert()
    {
        $adverts = Advert::all()->count();


        Advert::deleteCache($this->advert->id,'adverts');

        $adverts_after_store = Advert::all()->count();
        $this->assertEquals($adverts_after_store,$adverts-1);
    }

}
