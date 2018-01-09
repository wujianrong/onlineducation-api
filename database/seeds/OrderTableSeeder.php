<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'name' => '订单名称',
                'order_no' => '订单编号',
                'type' => 1,
                'status' => 1,
                'order_type_id' => 1,
                'title' => 1,
                'guest_id' =>1,
                'price' =>1,
                'mouth' =>1,
                'pay_type' => 1,
                'end' => Carbon::now(),
                'start' => Carbon::now(),
                'pay_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()->addDays(3),
            ],
        ]);
    }
}
