<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
namespace Database\Factories;
use App\Tag;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $serial = $faker->unique()->numerify('T#######');
    $uuid = $faker->unique()->uuid();
    $mac_addr = $faker->unique()->macAddress();
    return [
        'serial' => $serial,
        'uuid' => $uuid,
        'mac_addr' => $mac_addr,
        'user_id' => null,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
