<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VideoUrlTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('video_urls')->insert([
            [
                'id' => 1,
                'url' => '1111',
                'size' => 4,
                'duration' => '5秒',
                'video_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 2,
                'url' => '1111',
                'size' => 4,
                'duration' => '5秒',
                'video_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 3,
                'url' => '1111',
                'size' => 4,
                'duration' => '5秒',
                'video_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 4,
                'url' => '1111',
                'size' => 4,
                'duration' => '5秒',
                'video_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
