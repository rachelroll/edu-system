<?php

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'author' => $faker->name,
        'type' => rand(1,2),
        'title' => $faker->title,
        'content' => $faker->paragraph,
        'cover' => 'https://www.google.com/url?sa=i&rct=j&q=&esrc=s&source=images&cd=&cad=rja&uact=8&ved=2ahUKEwjvy7GYi97gAhXHi1QKHfabBTQQjRx6BAgBEAU&url=http%3A%2F%2Fwww.twoeggz.com%2Fnews%2F10427675.html&psig=AOvVaw12Uj8PNCOUhiXClO6GI0OH&ust=1551431587088034',
        'price' => rand(0,300),
        'is_free' => rand(0,1),
        'user_id' => rand(0,19),
        'series_id' => rand(0, 5)
    ];
});
