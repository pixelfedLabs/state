<?php

namespace App\Transformers;

use App\Actor;
use League\Fractal;
use App\Transformers\CreateNoteTransformer;

class ActivityPubOutboxTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = ['orderedItems'];

	public function transform(Actor $actor)
	{
		$count = $actor->incidents()->count();

		return [
			'@context'     => 'https://www.w3.org/ns/activitystreams',
			'id'           => $actor->permalink('/outbox'),
			'type'         => 'OrderedCollection',
			'totalItems'   => $count,
		];
	}

	public function includeOrderedItems(Actor $actor)
	{
		$incidents = $actor->incidents()->orderByDesc('created_at')->paginate(10);

		return $this->collection($incidents, new CreateNoteTransformer);
	}

}