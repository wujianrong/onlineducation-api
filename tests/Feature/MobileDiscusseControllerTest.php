<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileDiscusseControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function mobile_store_success_a_discusse()
    {
        $data = [
            'content' => '使用再加上我的自定义词汇'
        ];

        $response = $this->actingAs($this->guest_auth_password, 'api')
            ->json('POST', 'api/item/discusse/' . $this->lesson->id, $data);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function mobile_store_fail_a_discusse()
    {
        $data = [
            'content' => '使用再加上我的自定义词汇 枪支 毒品'
        ];

        $response = $this->actingAs($this->guest_auth_password, 'api')
            ->json('POST', 'api/item/discusse/' . $this->lesson->id, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status', 'word'
            ]);
    }

    /**
     * @test
     */
    public function mobile_lesson_discusses_list_data()
    {
        $response = $this->actingAs($this->auth_password, 'api')
            ->json('GET', 'api/item/discusse/' . $this->lesson->id . '/lesson_discusses');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data', 'links' => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta'        => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }
}
