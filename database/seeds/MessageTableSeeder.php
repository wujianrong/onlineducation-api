<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->insert([
            [
                'id' => 1,
                'title' => '这是第一个消息',
                'content' =>'这是第一个消息',
                'type' =>0,
                'user_id' => 1,
                'label' => 'label',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 2,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 3,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 4,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 5,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 6,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 7,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ], [
                'id' => 8,
                'title' => '这是第二个消息',
                'content' =>'这是第二个消息',
                'type' =>0,
                'label' => 'label',
                'user_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],

        ]);
    }
}
