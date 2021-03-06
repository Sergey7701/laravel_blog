<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Entry;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'text'      => $faker->text(100),
        'author_id' => User::inRandomOrder()->first()->id,
        'entry_id'  => Entry::inRandomOrder()->first()->id,
    ];
});
