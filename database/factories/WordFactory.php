<?php

/** @var Factory $factory */

use App\Meaning;
use App\User;
use App\Word;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Word::class, function (Faker $faker) {
    return [
        'language_id' => $faker->numberBetween(1, 10),
        'meaning_id'  => Meaning::withTrashed()->first()->id,
        'text'        => $faker->sentence(3, true),
        'comment'     => $faker->sentence(6, true),
        'user_id'     => User::first()->id
    ];
});
