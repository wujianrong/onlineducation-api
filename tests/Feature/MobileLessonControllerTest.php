<?php

namespace Tests\Feature;

use App\Models\Lesson;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileLessonControllerTest extends BaseControllertTest
{

    /**
     * @test
     */
    public function visit_mobile_lessons_paginate_data()
    {

        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/index');

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['last_lessons','nav','lesson_names']);
    }

    /** @test */
    public function visit_a_lesson_datas_page()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','/api/item/lesson/'.$this->lesson->id.'/edit');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'title','type','status','pictrue','price','like' ,
                'is_like', 'is_collect','is_show','is_learned','play_times','for','learning' ,
                'describle','educational','nav','teacher','genres','sections','created_at'
            ])
        ;
    }

    /**
     * @test
     */
    public function visit_mobile_nav_lessons_paginate_data()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/'.$this->nav->id.'/nav_lessons');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data','links' =>[
                    'first','last','next','prev'
                ]
                ,'meta'=> [
                    'current_page','from','last_page','path','per_page','to','total'
                ]
            ]);
    }

    /**
     * @test
     */
    public function visit_mobile_genre_lessons_paginate_data()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/'.$this->nav->id.'/nav/'.$this->genre->id.'/genre_lessons');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data','links' =>[
                    'first','last','next','prev'
                ]
                ,'meta'=> [
                    'current_page','from','last_page','path','per_page','to','total'
                ]
            ]);
    }

    /**
     * @test
     */
    public function visit_guest_pay_lessons_paginate_data()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/pay_orders');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data','links' =>[
                    'first','last','next','prev'
                ]
                ,'meta'=> [
                    'current_page','from','last_page','path','per_page','to','total'
                ]
            ]);
    }

    /**
     * @test
     */
    public function visit_guest_collect_lessons_paginate_data()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/collect_lessons');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data','links' =>[
                    'first','last','next','prev'
                ]
                ,'meta'=> [
                    'current_page','from','last_page','path','per_page','to','total'
                ]
            ]);
    }

    /**
     * @test
     */
    public function collect_a_lesson_with_guest()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/'.$this->lesson->id.'/collect');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function like_a_lesson_with_guest()
    {
        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/'.$this->lesson->id.'/like');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function search_lessons_whith_words_from_lesson_name()
    {
        factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'status' => 3,
            'type' => 1,
            'is_nav' => 1,
        ]);
        factory(Lesson::class)->create([
            'nav_id' =>   $this->nav->id,
            'teacher_id' =>   $this->teacher->id,
            'educational_id' =>   $this->educational->id,
            'status' => 3,
            'type' => 3,
            'is_nav' => 1
        ]);

        $response = $this->actingAs($this->guest_auth_password,'api')
            ->json('GET','api/item/lesson/search?word=&page=0&pagesize=8');


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [ '*' => ['id','name','title','pictrue','price'] ]
            ]);
    }
}
