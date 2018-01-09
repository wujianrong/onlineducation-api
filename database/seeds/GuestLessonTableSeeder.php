<?php

use Illuminate\Database\Seeder;

class GuestLessonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guest_lesson')->insert([
            [
                'guest_id' => 1,
                'lesson_id' => 1,
            ]
        ]);
    }
}
