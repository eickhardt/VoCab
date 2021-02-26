<?php

/** @var Factory $factory */

use App\Meaning;
use App\MeaningType;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Meaning::class, function (Faker $faker) {
    return [
        'meaning_type_id' => MeaningType::all()->random()->id,
        'root'            => $faker->sentence(3, true),
        'user_id'         => User::first()->id,
    ];
});
