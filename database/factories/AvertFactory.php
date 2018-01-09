<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Advert::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'path' => $faker->imageUrl(),
        'type' => 0,
        'order' => 1,
        'url' => $faker->imageUrl(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
