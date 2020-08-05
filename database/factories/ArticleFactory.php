<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
	return [
		'user_id' => '8',
		'title' => $faker->sentence,
		'contents' => $faker->paragraph,
		'article_type' => '/free',
		'priority' => '0'

	];
});
