<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Revision::class, function (Faker $faker) {
    return [
        'revisionable_type' => 'App\Models\User',
        'revisionable_id' => 1,
        'user_id' => 1,
        'key' => 'login',
        'old_value' => null,
        'new_value' =>Carbon::now(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(1),
    ];
});
