<?php

namespace Tests\Feature;


class PermissionControllerTest extends BaseControllertTest
{

    /** @test */
    public function visit_permissions_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/permission/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'permissions' =>[
                    '*' =>[
                        'id','name','display_name','created_at','updated_at','deleted_at'
                    ]
                ]
            ])
        ;
    }
    
    /** @test */
    public function visit_permission_edit_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/permission/'. $this->permission->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'permission' =>[
                    'id','name','display_name','created_at','updated_at','deleted_at'
                ]
            ])
        ;
    }

    /** @test */
    public function update_a_permision()
    {
        $data = [
            'name' => 'admin.create_role',
            'display_name' => '创建角色'
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/permission/'.$this->permission->id.'/update',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ])
        ;
    }

    /** @test */
    public function create_a_permission()
    {
        $data = [
            'name' => 'admin.create_role',
            'parent_id' => 0,
            'display_name' => '创建角色'
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/permission/create',$data);


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ])
        ;
    }
    
    /** @test */
    public function del_a_permission()
    {

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/permission/'.$this->permission->id.'/delete');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ])
        ;
    }
}
