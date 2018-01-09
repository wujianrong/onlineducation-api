<?php

use Illuminate\Database\Seeder;

class GenreLessonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genre_lesson')->insert([
            [
                'genre_id' => 1,
                'lesson_id' => 1,
            ]
        ]);
    }
}
