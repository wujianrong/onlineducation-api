<?php

namespace Tests\Feature;



class SectionControllerTest extends BaseControllertTest
{

    /** @test */
    public function store_a_section()
    {
        $data = [
            'name' => '第一节',
            'is_free' => 0,
            'lesson_id' => $this->lesson->id,
            'video_id' => $this->video->id,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/lesson/'.$this->lesson->id.'/section',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }
    
    /** @test */
    public function update_a_section()
    {
        $data = [
            'name' => '第一节',
            'is_free' => 0,
            'lesson_id' => $this->lesson->id,
        ];

        $response = $this->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/lesson/section/'.$this->section->id.'/update',$data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }

    /** @test */
    public function delete_a_section()
    {
        $response = $this->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/lesson/section/'.$this->section->id.'/delete');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status','message'
            ]);
    }


    
}
