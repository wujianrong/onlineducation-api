<?php

namespace Tests\Unit;

use App\Models\Genre;
use Tests\Feature\BaseControllertTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreTest extends BaseControllertTest
{
    /** @test */
    public function udate_a_genre()
    {
        $data = [
            'name' => '更新关务风险',
        ];

        $genre = Genre::updateCache($this->genre->id,$data,'genres');

        $this->assertEquals('更新关务风险',$genre->name);
    }

    /** @test */
    public function create_a_genre()
    {
        $genres = Genre::all()->count();

        $data = [
            'name' => '关务风险',
        ];

        Genre::storeCache($data,'genres');

        $genres_after_store = Genre::all()->count();
        $this->assertEquals($genres_after_store,$genres+1);
    }

    /** @test */
    public function success_delete_a_genre()
    {
        $genre_no_lesson = factory(Genre::class)->create();
        $genres = Genre::all()->count();

        Genre::deleteCache($genre_no_lesson->id,'genres');

        $genres_after_del = Genre::all()->count();
        $this->assertEquals($genres_after_del,$genres-1);
    }

}
