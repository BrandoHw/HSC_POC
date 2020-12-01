<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {

    $name = $faker->unique()->numerify('Project ###');

    $today = Carbon::now();
    $start_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp());
    $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $start_date)->addDay();
    
    $start_date = $start_date->format('Y-m-d');
    $end_date = $end_date->format('Y-m-d');
    
    return [
        'name' => $name,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
