<?php

namespace Tests\Feature;



use App\Models\Teacher;

class TeacherControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_teachers_lsits_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/teacher/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','office','avatar','created_at'
                ]
            ]);
    }

    /**
     * @test
     */
    public function visit_teachers_names()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/teacher/names');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function visit_teacher_data_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','api/admin/teacher/'.$this->teacher->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'teacher' => [
            		'id','name','office','avatar','created_at'
				],'teacher_names'
            ]);
    }

    /**
     * @test
     */
    public function create_a_teacher()
    {
        $data = [ 
            'name' => '你好',
            'office' => '你好',
            'avatar' => 'http://sfdsf',
            'describle' => 'sdfdsfdsfdsfdsfdsfsdfdsfsdfd',
        ];

        $response = $this->actingAs($this->auth_password, 'api')
            ->json('POST', 'api/admin/teacher/',$data);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function update_a_teacher()
    {
        $data = [
            'name' => '你好',
            'office' => '你好',
            'avatat' => 'http://sfdsf',
            'describle' => 'sdfdsfdsfdsfdsfdsfsdfdsfsdfd',
        ];

        $response = $this->actingAs($this->auth_password, 'api')
            ->json('POST', 'api/admin/teacher/' . $this->teacher->id.'/update',$data);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function success_delete_a_teacher()
    {
        $teacher = factory(Teacher::class)->create();
        $response = $this->actingAs($this->auth_password, 'api')
            ->json('GET', 'api/admin/teacher/' . $teacher->id.'/delete');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function fail_delete_a_teacher()
    {
        $response = $this->actingAs($this->auth_password, 'api')
            ->json('GET', 'api/admin/teacher/' . $this->teacher->id.'/delete');

        $response->assertStatus(201);
    }
}
