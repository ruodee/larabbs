<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
	$sentence = $faker->sentence();

	//随机取一个月内的时间
	$updated_at = $faker->dateTimeThisMonth();
	//给dataTimeThisMonth()传递一个参数，参数是生成随机本月时间的最大截止时间，这样保证创建时间小于更新时间。
	$created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'title' => $sentence,
        'body' => $faker->text(),
        'excerpt' => $sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
