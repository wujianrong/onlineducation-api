<?php

namespace Tests\Feature;


use Illuminate\Support\Facades\Hash;

class UserControllerTest extends BaseControllertTest
{

    /** @test */
    public function get_auth_user_data()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/user/me');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id','name','created_at',
            ])
        ;
    }

    /** @test */
    public function visit_users_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/user/lists');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','nickname','frozen','gender','created_at','role'
                ]
            ])
        ;
    }

	/** @test */
	public function visit_users_names_data()
	{
		$response = $this
			->actingAs($this->auth_password,'api')
			->json('GET','/api/admin/user/names');

		$response->assertStatus(200);
	}

    /** @test */
    public function visit_user_edit_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/user/'.$this->user->id.'/edit');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'user' => [
            		'id','name','nickname','frozen','gender','created_at',
					'role' => [
						'*' => [
							'id','name','display_name','created_at','updated_at','deleted_at'
						]
					]
                ],'user_names'
            ])
        ;
    }
    

    /** @test */
    public function update_a_user()
    {

        $data = [
            'name' => 'admin',
            'nickname' => '超级管理员',
            'frozen' => 0,
            'gender' => 1,
            'password' => 'admin',
            'role' => [ $this->role->id]
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/user/'.$this->user->id.'/update',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ])
        ;
    }

    /** @test */
    public function store_a_user()
    {

        $data = [
            'name' => 'admin',
            'nickname' => '超级管理员',
            'frozen' => 0,
            'gender' => 1,
            'password' => 'admin',
            'role' => [ $this->role->id]
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/user/store',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ])
        ;
    }

    /** @test */
    public function del_a_user()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/user/'.$this->user->id.'/delete');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ])
        ;
    }
    
    /** @test */
    public function frozen_a_user()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/user/'.$this->user->id.'/frozen');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ])
        ;
    }

    /** @test */
    public function refrozen_a_user()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/user/'.$this->user->id.'/refrozen');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ])
        ;
    }

}
