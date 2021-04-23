<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
namespace Database\Factories;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Hash;
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
    
    $fName = $faker->firstName();
    $lName = $faker->lastName();
    
    $firstName = strtolower($fName);
    $lastName = strtolower($lName);
    
    $username = $firstName[0].$lName;
    $email = $firstName.".".$lastName."@example.com";
    $phoneNum = $faker->e164PhoneNumber();
    $password = $firstName[0].$lName."@123";

    return [
        'fName' => $fName,
        'lName' => $lName,
        'username' => $username,
        'gender' => 'M',
        'email' => $email,
        'phone_number' => $phoneNum,
        // 'email_verified_at' => now(),
        'password' => Hash::make($password),
        // 'tag_id' => $faker->unique()->numberBetween($min = 1, $max = 19),
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        // 'remember_token' => Str::random(10),
    ];
});
