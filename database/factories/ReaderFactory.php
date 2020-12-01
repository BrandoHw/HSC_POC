<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reader;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Reader::class, function (Faker $faker) {
    $serial = $faker->unique()->numerify('R#######');
    $uuid = $faker->unique()->uuid();
    $mac_addr = $faker->unique()->macAddress();
    return [
        'serial' => $serial,
        'uuid' => $uuid,
        'mac_addr' => $mac_addr,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
