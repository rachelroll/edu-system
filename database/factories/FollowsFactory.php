<?php

use Faker\Generator as Faker;

$factory->define(\App\Follow::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 20),
        'fans_id' => rand(1, 20)
    ];
});
