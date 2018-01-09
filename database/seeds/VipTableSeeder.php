<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vips')->insert([
            [
                'name' => '年vip',
                'status' => 1,
                'expiration' => 1,
                'price' => 1.00,
                'count' => 1,
                'describle' => '这个是一个年vip',
                'up' => Carbon::now(),
                'down' => Carbon::now()->addMonth(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
