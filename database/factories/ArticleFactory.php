<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'header'      => $faker->sentence(5),
        'description' => $faker->text(100),
        'text'        => $faker->text(500),
        'publish'     => $faker->boolean
    ];
});
