<?php

namespace App\Transformers;

use App\Incident;
use League\Fractal;

class CreateNoteTransformer extends Fractal\TransformerAbstract {

	public function transform(Incident $incident)
	{
		$actor = $incident->service->actor;
		return [
			'@context' => [
				'https://www.w3.org/ns/activitystreams',
				'https://w3id.org/security/v1',
			],
			'id' 					=> $incident->permalink('#activity'),
			'type' 					=> 'Create',
			'actor' 				=> $actor->permalink(),
			'published' 			=> $incident->created_at->toAtomString(),
			'to' 					=> [
				"https://www.w3.org/ns/activitystreams#Public"
			],
			'cc' 					=> [
				$actor->permalink('/followers')
			],
			'object' => [
				'id' 				=> $incident->permalink(),
				'type' 				=> 'Note',
				'summary'   		=> null,
				'content'   		=> $incident->title,
				'inReplyTo' 		=> null,
				'published'    		=> $incident->created_at->toAtomString(),
				'url'          		=> $incident->url(),
				'attributedTo' 		=> $actor->permalink(),
				'to' 					=> [
					"https://www.w3.org/ns/activitystreams#Public"
				],
				'cc' 					=> [
					$actor->permalink('/followers')
				],
				'sensitive'       	=> false
			]
		];
	}

}