<?php

namespace Tests\Feature;

use App\Models\Genre;

class GenreControllerTest extends BaseControllertTest
{

    /** @test */
    public function visit_genres_lists_page()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/genre/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id','name','created_at'
                ]
            ]);
    }

    /** @test */
    public function visit_genres_names()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/genre/names');

        $response
            ->assertStatus(200);
    }

    /** @test */
    public function visit_a_genre()
    {
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/genre/'.$this->genre->id);


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
            	'genre' => ['id','name','created_at'],'genre_names'
            ]);
    }

    /** @test */
    public function udate_a_genre()
    {

        $data = [
            'name' => '更新关务风险',
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/genre/'.$this->genre->id.'/update',$data);

        $response->assertStatus(200);

    }

    /** @test */
    public function create_a_genre()
    {

        $data = [
            'name' => '关务风险',
        ];

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('POST','/api/admin/genre/',$data);

        $response->assertStatus(200);

    }

    /** @test */
    public function success_delete_a_genre()
    {

        $genre_no_lesson = factory(Genre::class)->create();
        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/genre/'.$genre_no_lesson->id.'/delete');

        $response->assertStatus(200);

    }

    /** @test */
    public function fail_delete_a_genre()
    {
        $this->genre->lessons()->sync([$this->lesson->id]);

        $response = $this
            ->actingAs($this->auth_password,'api')
            ->json('GET','/api/admin/genre/'.$this->genre->id.'/delete');

        $response->assertStatus(201);
    }
}
