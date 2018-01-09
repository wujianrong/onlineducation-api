<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AuthPasswordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auth_passwords')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'password' => bcrypt('admin'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
//            ,[
//                //微信用户
//                'id' => 2,
//                'name' => 'Dream',
//                'password' => bcrypt('oDMF40TjhXnYMy0e5RLPX3ZU-kzw'),
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
        ]);
    }
}
