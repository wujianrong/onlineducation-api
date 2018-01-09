<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Discusse::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
        'lesson_id' => 1,
        'guest_id' => 1,
        'is_better' => false,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
