<?php

namespace Tests\Feature;


use App\Models\Nav;

class NavControllerTest extends BaseControllertTest
{

    /** @test */
    public function visit_navs_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/nav/lists');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','pictrue','created_at','lessons','lesson_names'
                ]
            ]);
    }

    /** @test */
    public function visit_navs_names()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/nav/names');

        $response
            ->assertStatus(200);
    }

    /** @test */
    public function visit_a_nav_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/nav/'.$this->nav->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'nav' => ['id','name','pictrue','created_at','lessons','lesson_names'],'nav_names'

            ]);
    }
    
    /** @test */
    public function update_a_nav()
    {
        $data = [
            'name' => '免费',
            'order_type' => 1,
            'pictrue' => 'http://123',
            'lesson_ids' => [$this->lesson->id],
        ];
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/nav/'.$this->nav->id.'/update',$data);

        $response->assertStatus(200);
    }

    /** @test */
    public function create_a_nav()
    {
        $data = [
            'name' => '免费',
            'order_type' => 1,
            'pictrue' => 'http://123',
        ];
        
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/nav/',$data);
        
        $response->assertStatus(200);
    }

    /** @test */
    public function success_delete_a_nav()
    {
        $nav_no_lesson = factory(Nav::class)->create();
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/nav/'.$nav_no_lesson->id.'/delete');

        $response->assertStatus(200);
    }

    /** @test */
    public function fail_delete_a_nav()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/nav/'.$this->nav->id.'/delete');

        $response->assertStatus(201);
    }
  
}
