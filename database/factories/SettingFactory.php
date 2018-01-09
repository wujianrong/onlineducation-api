<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Setting::class, function (Faker $faker) {
    return [
        'index_type' => 1,
        'index_count' => 4,
        'vip_send_seting' => 10,
        'wechat_sub' => $faker->name,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
