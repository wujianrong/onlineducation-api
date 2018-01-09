<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\AuthPassword::class, function (Faker $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'password' => $password ?: $password = bcrypt('12345'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
