<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => 1,
        'order_no' => $faker->name,
        'status' => 1,
        'title' => 1,
        'order_type_id' => 1,
        'guest_id' =>1,
        'price' =>1,
        'mouth' =>1,
        'pay_type' => $faker->randomNumber(),
        'end' => Carbon::now(),
        'start' => Carbon::now(),
        'pay_date' => Carbon::now(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
