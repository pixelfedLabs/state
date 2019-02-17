<?php

namespace App\Transformers;

use App\Actor;
use League\Fractal;

class ActivityPubActorTransformer extends Fractal\TransformerAbstract {

	public function transform(Actor $actor)
	{
		return [
			'@context' => 'https://www.w3.org/ns/activitystreams',
			'id' => $actor->permalink(),
			'type' => 'Person',
			'inbox' => $actor->permalink('/inbox'),
			'outbox' => $actor->permalink('/outbox'),
			'name' => $actor->system->name,
			'preferredUsername' => 'state_' . $actor->id,
			'url' => $actor->permalink(),
			'publicKey' => [
				'id'           => $actor->permalink().'#main-key',
				'owner'        => $actor->permalink(),
				'publicKeyPem' => $actor->public_key,
			],
		];
	}

}