<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\IncidentUpdate::class, function (Faker $faker) {
	$incident = App\Incident::inRandomOrder()->firstOrFail();
	return [
		'system_id' => $incident->system->id,
		'service_id' => $incident->service->id,
		'incident_id' => $incident->id,
		'slug' => (string) Str::uuid(),
		'state' => 'investigating',
		'description' => $faker->sentence
	];
});
