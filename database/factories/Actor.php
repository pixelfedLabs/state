<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Actor::class, function (Faker $faker) {
	$system = App\System::inRandomOrder()->firstOrFail();

	$pkiConfig = [
		'digest_alg'       => 'sha512',
		'private_key_bits' => 2048,
		'private_key_type' => OPENSSL_KEYTYPE_RSA,
	];
	$pki = openssl_pkey_new($pkiConfig);
	openssl_pkey_export($pki, $pki_private);
	$pki_public = openssl_pkey_get_details($pki);
	$pki_public = $pki_public['key'];
    return [
        'username' => (string) Str::uuid(),
        'system_id' => $system->id,
        'public_key' => $pki_public,
        'private_key' => $pki_private
    ];
});
