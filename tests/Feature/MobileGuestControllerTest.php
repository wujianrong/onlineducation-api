<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileGuestControllerTest extends BaseControllertTest
{
    /** @test */
    public function visit_guest_profile_data_page()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/guest/profile');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'guest' => [
                    'id','nickname','phone','picture','gender','created_at','is_expire','vip_name','vip_end_date'
                ],
                'messages_count'

            ]);
    }

    /** @test */
    public function send_a_sms_code_to_wechat_phone()
    {
        $data = [
            'phone' => 13412081338
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/item/guest/send_sms/',$data);

        $response->assertStatus(200);
    }

    /** @test */
    public function check_the_sms_code_if_false()
    {
        $data = [
            'sms_code' => Cache::get('sms_code'.Carbon::now()->timestamp),
            'phone' => 13412081338
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/item/guest/'.$this->guest->id.'/check_tel/',$data);

        $response->assertStatus(200);
    }
}
