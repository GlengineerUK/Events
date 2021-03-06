<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\WorkerType;
use Faker\Generator as Faker;

$factory->define(WorkerType::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence(),
    ];
});
