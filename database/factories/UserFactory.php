<?php

use App\User;
use Illuminate\Support\Str;
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

public function run()
{
    factory(App\User::class, 50)->create()->each(function ($user) {
//        $user->posts()->save(factory(App\UserProfile::class)->make());
    });
}
