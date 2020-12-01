<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Building::class, function (Faker $faker) {
    $name = $faker->unique()->numerify('Building ###');
    $floor_num = $faker->numberBetween(1, 5);
    $address = $faker->address();
    $lat = $faker->latitude();
    $lng = $faker->longitude();

    return [
        'name' => $name,
        'floor_num' => $floor_num,
        'address' => $address,
        'lat' => $lat,
        'lng' => $lng,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
