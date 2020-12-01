<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    $name = $faker->unique()->numerify('Group #');

    return [
        'name' => $name,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
