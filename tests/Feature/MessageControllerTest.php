<?php

namespace Tests\Feature;


class MessageControllerTest extends BaseControllertTest
{
    /**
     * @test
     */
    public function visit_messages_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/message/lists');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','title','type','content','user','label','created_at'
                ]
            ]);
    }

    /**
     * @test
     */
    public function send_some_messages()
    {
        $data = [
            'message' => '发送一条消息',
            'label_id' => $this->label->id,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/message/send_messages',$data);

        $response->assertStatus(200);
    }

}
