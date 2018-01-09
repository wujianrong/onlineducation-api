<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LessonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lessons')->insert([
            [
                'id' => 1,
                'name' => '关务系列第一个课程',
                'title' => '关务系列第一个课程，这是东华做好的时代',
                'type' => 1,
                'educational_id' => 1,
                'nav_id' => 1,
                'teacher_id' => 1,
                'status' => 1,
                'pictrue' => 'https://ss0.baidu.com/6ONWsjip0QIZ8tyhnq/it/u=2192770053,747833427&fm=173&s=A2A96EA546B2E9EF0698047A0300A072&w=640&h=790&img.JPEG',
                'price' => 100.00,
                'like' => 1,
                'for' => '年轻人--老年人--小孩子',
                'learning' => '关务系列第一个课程，这是东华做好的时代',
                'describle' => '关务系列第一个课程，这是东华做好的时代',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
