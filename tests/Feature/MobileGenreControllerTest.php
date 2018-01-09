<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileGenreControllerTest extends BaseControllertTest
{
    /** @test */
    public function visit_mobile_genres_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/item/genre/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name'
                ]
            ]);
    }
}
