<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Lesson::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'title' => $faker->paragraph,
        'type' => 1,
        'teacher_id' => 1,
        'nav_id' => 0,
        'educational_id' => 1,
        'status' => 1,
        'pictrue' => $faker->word,
        'price' => 0.00,
        'like' => 0,
        'learning' => '1213--12312--321',
        'for' =>  $faker->paragraph,
        'describle' =>  $faker->paragraph,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
    ];
});
