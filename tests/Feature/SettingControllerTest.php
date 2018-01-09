<?php

namespace Tests\Feature;



class SettingControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_setting_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/setting/'.$this->setting->id);
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id','index_type','index_count','vip_send_seting','created_at'
            ]);
    }

    /**
     * @test
     */
    public function set_index_type()
    {
        $data = [
            'index_type' => 1,
            'index_count' => 4,
            'vip_send_seting' => 7,
            'wechat_sub' => '微信关注回复',
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/setting/'.$this->setting->id.'/set_index_type',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }
}
