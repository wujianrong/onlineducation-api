<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DiscusseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discusses')->insert([
            [
                'id' => 1,
                'content' => '这个课程很有用',
                'lesson_id' => 1,
                'guest_id' => 1,
                'is_better' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 2,
                'content' => '这个课程很好看',
                'lesson_id' => 1,
                'guest_id' => 1,
                'is_better' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 3,
                'content' => '讲师讲的不错',
                'lesson_id' => 1,
                'guest_id' => 1,
                'is_better' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 4,
                'content' => '再来一段笑话就好了',
                'lesson_id' => 1,
                'guest_id' => 1,
                'is_better' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],[
                'id' => 5,
                'content' => '东华国际威武',
                'lesson_id' => 1,
                'guest_id' => 1,
                'is_better' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
