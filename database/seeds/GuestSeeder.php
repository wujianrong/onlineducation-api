<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GuestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guests')->insert([
            [
                'id' => 1,
                'nickname' => 'Dream',
                'phone' => null,
                'openid' => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
                'picture' => 'http://wx.qlogo.cn/mmopen/C6FZHjYVZVCpzGgCMYcQliab7RzjwZBfSgGiacJA4cbtgPhkNSBLWyZxsVIYOPm8SDL3ib7a0Gl5xbnQo8B800iafQvNN1UaA1uP/0',
                'gender' => 2,
                'position' => '北京',
                'auth_password_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ]
        ]);
    }
}
