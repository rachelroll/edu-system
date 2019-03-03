<?php

use Faker\Generator as Faker;

$factory->define(\App\Comment::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'comments' => $faker->paragraph,
        'user_id' => rand(0,30),
        'post_id' => rand(0,40),
    ];
});
