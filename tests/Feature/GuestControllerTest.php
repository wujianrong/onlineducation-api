<?php

namespace Tests\Feature;


use Illuminate\Support\Facades\Cache;

class GuestControllerTest extends BaseControllertTest
{
    /** @test */
    public function visit_guests_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/guest/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','nickname','phone','picture','gender','created_at','labels','label_ids'
                ]
            ]);
    }

    /** @test */
    public function visit_guest_data_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/guest/'.$this->guest->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id','nickname','phone','picture','gender','created_at','labels','label_ids',
            ]);
    }

    /**
     * @test
     */
    public function add_tow_labels_to_guest()
    {
        $data = [
            'label_ids' => [
                $this->label->id,
            ]
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','api/admin/guest/'.$this->guest->id.'/set_label',$data);
        
        $response->assertStatus(200);
    }

}
