<?php

use Illuminate\Database\Seeder;

class GuestMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guest_message')->insert([
            [
                'guest_id' => 1,
                'message_id' => 1,
            ],[
                'guest_id' => 1,
                'message_id' => 2,
            ],[
                'guest_id' => 1,
                'message_id' => 3,
            ],[
                'guest_id' => 1,
                'message_id' => 4,
            ],[
                'guest_id' => 1,
                'message_id' => 5,
            ],
        ]);
    }
}
