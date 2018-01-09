<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileMessageControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_mobile_messages_lists_page()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/message/lists');


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data','links' =>[
                    'first','last','next','prev'
                ]
                ,'meta'=> [
                    'current_page','from','last_page','path','per_page','to','total'
                ]
            ]);
    }
}
