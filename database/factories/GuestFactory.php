<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Guest::class, function (Faker $faker) {
    return [
        'nickname' => $faker->name,
        'phone' => $faker->phoneNumber,
        'openid' => $faker->name,
        'picture' => $faker->imageUrl(),
        'gender' => $faker->boolean,
        'auth_password_id' =>1,
        'position' =>$faker->address,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
