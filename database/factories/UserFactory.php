<?php

/** @var Factory $factory */

use App\User;
use App\WordLanguage;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'             => $faker->name,
        'email'            => $faker->unique()->safeEmail,
        'password'         => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token'   => Str::random(10),
        'is_first_login'   => false,
        'is_admin'         => false,
        'root_language_id' => WordLanguage::orderBy('id', 'desc')->first()->id,
        'is_porting'       => false,
    ];
});
