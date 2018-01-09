<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileVipControllerTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function visit_mobile_vips_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/item/vip/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','status','expiration','price','count','describle','up','down','created_at'
                ]
            ]);
    }
}
