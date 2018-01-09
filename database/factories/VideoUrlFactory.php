<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\VideoUrl::class, function (Faker $faker) {
    return [
        'url' => '1111',
        'size' => 4,
        'duration' => '5秒',
        'video_id' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
