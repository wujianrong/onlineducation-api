<?php

namespace Tests\Feature;


use App\Models\Role;

class RoleControllerTest extends BaseControllertTest
{

    /** @test */
    public function visit_roles_lists_page()
    {
        $response = $this
             ->actingAs( $this->auth_password,'api')
            ->json('GET','/api/admin/role/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','display_name','created_at'
                ]
            ]);
    }

    /** @test */
    public function visit_role_edit_page()
    {
        $response = $this
             ->actingAs( $this->auth_password,'api')
            ->json('GET','/api/admin/role/'.$this->role->id.'/edit');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'role' => ['id','name','display_name','created_at']
            ])
        ;
    }


    /** @test */
    public function get_all_permissions()
    {
        $response = $this
            ->actingAs( $this->auth_password,'api')
            ->json('GET','/api/admin/role/create');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'permissions' =>[
                    '*' =>[
                        'id','name','display_name','created_at','updated_at','deleted_at',
                        'subPermissions' => [
                            '*' =>[
                                'id','name','display_name','created_at','updated_at','deleted_at'
                            ]
                        ]
                    ]
                ]
            ])
        ;
    }

    /** @test */
    public function update_a_role()
    {

        $data = [
            'name' => 'admin',
            'display_name' => '超级管理员',
            'permission_ids' => [ $this->permission->id]
        ];


        $response = $this
             ->actingAs( $this->auth_password,'api')
            ->json('POST','/api/admin/role/'.$this->role->id.'/update',$data);

        $response->assertStatus(200);
    }

    
    /** @test */
    public function store_a_role()
    {

        $data = [
            'name' => 'admin',
            'display_name' => '超级管理员',
            'permission_ids' => [ $this->permission->id]
        ];

        $response = $this
             ->actingAs( $this->auth_password,'api')
            ->json('POST','/api/admin/role/store',$data);

        $response->assertStatus(200);
    }

    /* @test */
    public function success_del_a_role()
    {
        $response = $this
            ->actingAs( $this->auth_password,'api')
            ->json('GET','/api/admin/role/'.$this->role->id.'/delete');

        $response->assertStatus(200);
    }

    /* @test */
    public function fail_del_a_role()
    {
        $role = factory(Role::class)->create();
        $role->users()->sync([$this->user->id]);

        $response = $this
            ->actingAs( $this->auth_password,'api')
            ->json('GET','/api/admin/role/'.$role->id.'/delete');

        $response->assertStatus(201);
    }
}
