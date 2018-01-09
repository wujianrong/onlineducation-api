<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileSettingControllerTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function visit_mobile_setting_page()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/setting/'.$this->setting->id);
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [ 'id','index_type','index_count','vip_send_seting','created_at']
            ]);
    }
}
