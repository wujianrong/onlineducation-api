<?php

namespace Tests\Feature;



use App\Models\Lesson;

class LessonControllerTest extends BaseControllertTest
{
    /** @test */
    public function visit_five_lessons_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'nav' => [
                    '*' => [
                        'id','name','pictrue','created_at',
                     ]

                ],
                'lessons' => [
                    '*' => [
                        'id','name','title','type','nav','status','pictrue','students','price','created_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function visit_five_lessons_names()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/names');

        $response
            ->assertStatus(200);
    }

    /** @test */
    public function visit_up_lessons_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/up_lesson_list');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','pictrue','created_at'
                ]
            ]);
    }

    /** @test */
    public function visit_index_seting_lessons_lists_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/index_seting_list');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'lessons' => [
                    '*' => [
                        'id','name','pictrue','created_at'
                    ]
                ]
            ]);
    }
    
    /** @test */
    public function visit_a_lesson_datas_page()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$this->lesson->id);
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'lesson' => [
            		'id','name','title','type','nav','educational','teacher','status','pictrue','students',
							 'price','for','describle','created_at','genres','play_times'
				],'lesson_names'

            ])
        ;
    }
    
    /** @test */
    public function store_a_lesson()
    {
        $data = [
            'name' => 'aeo',
            'title' => 'aeo',
            'type' => 0,
            'educational_id' => $this->educational->id,
            'nav_id' => $this->nav->id,
            'teacher_id' => $this->teacher->id,
            'status' => 0,
            'pictrue' => 0,
            'section_ids' => [$this->section->id],
            'genre_ids'  => [$this->genre->id],
            'learning' => [123,232,4353],
            'price' => 0,
            'like' => 0,
            'for' => 0,
            'describle' => 0,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/lesson/',$data);

        $response->assertStatus(200);

    }
    
    /** @test */
    public function update_a_lesson()
    {
        $data = [
            'name' => '更新课程名称',
            'title' => '更新课程title',
            'type' => 1,
            'educational_id' => $this->educational->id,
            'nav_id' => $this->nav->id,
            'teacher_id' => $this->teacher->id,
            'status' => 1,
            'pictrue' => 'http://test',
            'price' => 1.00,
            'section_ids' => [$this->section->id],
            'is_frees' => [$this->section->id],
            'genre_ids'  => [$this->genre->id],
            'learning' => [123,232,4353],
            'like' => 1,
            'for' => '11-11-1',
            'describle' => '更新课程描述',
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/lesson/'.$this->lesson->id.'/update',$data);


        $response->assertStatus(200);
    }

    /** @test */
    public function success_delete_a_lesson()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$this->lesson->id.'/delete');

        $response->assertStatus(200);
    }

    /** @test */
    public function fail_delete_a_lesson_is_nav()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'is_nav' => 1,
        ]);

        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$lesson->id.'/delete');

        $response->assertStatus(201);
    }

    /** @test */
    public function fail_delete_a_lesson_is_up()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'is_nav' => 1,
            'status' => 3
        ]);

        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$lesson->id.'/delete');

        $response->assertStatus(201);
    }


    /** @test */
    public function up_a_lesson()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$this->lesson->id.'/up');

        $response->assertStatus(200);
    }

    /** @test */
    public function success_down_a_lesson()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$this->lesson->id.'/down');

        $response->assertStatus(200);
    }

    
    /** @test */
    public function fail_down_a_lesson()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'is_nav' => 1,
        ]);

        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$lesson->id.'/down');

        $response->assertStatus(201);
    }

    /** @test */
    public function get_students_data()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/'.$this->lesson->id.'/student/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'nickname','phone','picture','gender','learned_per','add_date'
                ]
            ]);
    }
}
