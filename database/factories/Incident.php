<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Incident::class, function (Faker $faker) {
	$service = App\Service::inRandomOrder()->firstOrFail();
	return [
		'system_id' => $service->system->id,
		'service_id' => $service->id,
		'slug' => (string) Str::uuid(),
		'title' => $faker->catchPhrase,
		'state' => 'resolved',
		'resolved_at' => now()->addHours(2)
	];
});
