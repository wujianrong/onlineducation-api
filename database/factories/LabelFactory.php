<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Label::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
