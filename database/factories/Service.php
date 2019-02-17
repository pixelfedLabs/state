<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Service::class, function (Faker $faker) {
	$system = App\System::inRandomOrder()->firstOrFail();
	$name = $faker->unique()->company;
	$desc = $faker->catchPhrase;
	return [
		'system_id' => $system->id,
		'name' => $name,
		'slug' => Str::slug($name),
		'description' => $desc,
		'tooltip' => $desc,
		'active' => false
	];
});
