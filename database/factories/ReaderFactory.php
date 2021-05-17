<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
namespace Database\Factories;
use App\Reader;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Reader::class, function (Faker $faker) {
    $serial = $faker->unique()->numerify('R#######');
    $mac_addr = $faker->unique()->macAddress();
    return [
        'serial' => $serial,
        'uuid' => $uuid,
        'mac_addr' => $mac_addr,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
