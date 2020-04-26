<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'header'      => $faker->sentence(5),
        'text'        => $faker->text(500),
        'publish'     => $faker->boolean,
    ];
});
