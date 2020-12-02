<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
namespace Database\Factories;
use App\Company;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    $name = $faker->unique()->company();
    
    return [
        'name' => $name,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
