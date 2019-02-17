<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\System::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'description' => $faker->catchPhrase,
        'url' => $faker->url,
        'active' => false
    ];
});
