<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\SensorData::class, function (Faker\Generator $faker) {
    return [
        'date_time' => $faker->dateTime,
        'liters' => $faker->randomFloat(2, 0, 5),
        'average_flow' => $faker->$faker->randomFloat(8, 0, 1)
    ];
});
