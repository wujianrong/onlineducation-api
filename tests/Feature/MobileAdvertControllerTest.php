<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileAdvertControllerTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function visit_mobile_adverts_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/item/advert/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','path','type','url','order','created_at'
                ]
            ]);
    }
}
