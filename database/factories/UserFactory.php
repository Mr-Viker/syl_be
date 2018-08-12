<?php

use App\Models\User;
use Faker\Generator as Faker;

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
    // $password = \Hash::make('1');
    $password = \Crypt::encrypt('1');
    return [
        'username' => $faker->userName,
        'phone' => $faker->unique()->randomNumber(),
        'password' => $password, // secret
    ];
});
