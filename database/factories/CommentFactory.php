<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Comment;
use App\Entry;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $users_ids = User::pluck('id')->toArray();
    $entries_ids = Entry::pluck('id')->toArray();
    return [
        'text'      => $faker->text(100),
        'author_id' => $users_ids[array_rand($users_ids)],
        'entry_id'  => $entries_ids[array_rand($entries_ids)],
    ];
});
