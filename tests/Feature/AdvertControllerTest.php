<?php

namespace Tests\Feature;


use App\Models\Advert;

class AdvertControllerTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function visit_adverts_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/advert/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','path','type','url','order','created_at'
                ]
            ]);
    }

    /**
     * @test
     */
    public function visit_advert_data_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/advert/'.$this->advert->id);
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id','name','path','url','type','order','created_at'
            ]);
    }

    /**
     * @test
     */
    public function update_a_advert()
    {
        $data = [
            'name' => '修改广告',
            'path' =>'修改广告地址',
            'order' => 3,
            'url' => '修改广告链接',
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/advert/'.$this->advert->id.'/update',$data);
   
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function store_a_advert()
    {

        $data = [
            'name' => '修改广告',
            'path' =>'修改广告地址',
            'order' => 3,
            'type' => 0,
            'url' => '修改广告链接',
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/advert/',$data);

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function delete_a_advert()
    {
        $adverts = Advert::all()->count();

        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/advert/'.$this->advert->id.'/delete');

        $response->assertStatus(200);
        $adverts_after_del = Advert::all()->count();
        $this->assertEquals($adverts_after_del,$adverts-1);
    }

}
