<?php

/** @var Factory $factory */

use App\Models\Post;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title'      => $faker->realText(255),
        'author_id'  => factory(User::class)->create()->id,
        'body'       => $faker->realTextBetween(3, 1000),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
    ];
});
