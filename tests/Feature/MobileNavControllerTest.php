<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileNavControllerTest extends BaseControllertTest
{
    /*========================================移动端测试===============================================*/

    /** @test */
    public function visit_mobile_navs_lists_page()
    {
        $response = $this
            ->actingAs($this->guest_auth_password,'api')
            ->json('GET','/api/item/nav/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name'
                ]
            ]);
    }
}
