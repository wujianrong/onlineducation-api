<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Role::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
