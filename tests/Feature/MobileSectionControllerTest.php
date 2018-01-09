<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileSectionControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_a_section_data()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','/api/item/lesson/section/'.$this->section->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','sections' => [
                    'id','name','lesson_name','is_free','is_learned','is_last_learned','play_times','video','created_at',
                ]
            ]);
    }

}
