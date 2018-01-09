<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\User::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'nickname' => $faker->name,
        'auth_password_id' =>1,
        'remember_token' => str_random(10),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});