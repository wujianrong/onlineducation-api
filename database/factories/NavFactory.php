<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Nav::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'pictrue' => $faker->imageUrl(),
        'order_type' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
