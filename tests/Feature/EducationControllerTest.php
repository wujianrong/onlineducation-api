<?php

namespace Tests\Feature;

use App\Models\Educational;

class EducationControllerTest extends BaseControllertTest
{
    
    /** @test */
    public function visit_eductions_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/education/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' =>[
                    'id','name','content','created_at'
                ]
            ]);
     }

    /** @test */
    public function create_a_eduction()
    {
        $data = [
            'name' => '付费模版',
            'content' => '11111111'
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/education/create',$data);

        $response->assertStatus(200);
    }

    /** @test */
    public function visit_a_eduction()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/education/'.$this->educational->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id','name','content','created_at'
            ]);
    }


    /** @test */
    public function update_a_eduction()
    {
        $data = [
            'name' => '更新付费模版',
            'content' => '更新付费模版内容'
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/education/'.$this->educational->id.'/update',$data);

        $response->assertStatus(200);
    }

    /** @test */
    public function success_del_a_eduction()
    {
        $educational = factory(Educational::class)->create();
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/education/'.$educational->id.'/delete');

        $response->assertStatus(200);
    }

    /** @test */
    public function fail_del_a_eduction()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/education/'.$this->educational->id.'/delete');

        $response->assertStatus(201);
    }
}

