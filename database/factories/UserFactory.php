<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    
    $firstName = $faker->unique()->firstName();
    $lastName = $faker->unique()->lastName();
    
    $name = $firstName." ".$lastName;

    $firstName = strtolower($firstName);
    $lastName = strtolower($lastName);
    
    $username = $firstName[0].".".$lastName;
    $email = $firstName.".".$lastName."@example.com";
    $password = $firstName[0].$lastName."@123";

    return [
        'name' => $name,
        'username' => $username,
        'email' => $email,
        // 'email_verified_at' => now(),
        'password' => bcrypt($password),
        // 'tag_id' => $faker->unique()->numberBetween($min = 1, $max = 19),
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        // 'remember_token' => Str::random(10),
    ];
});
