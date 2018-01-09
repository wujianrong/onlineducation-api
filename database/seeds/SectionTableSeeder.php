<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->insert([
            [
                'id' => 1,
                'name' => '这是一个免费章节',
                'is_free' => 1,
                'lesson_id' => 1,
                'video_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
