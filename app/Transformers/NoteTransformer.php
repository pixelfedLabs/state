<?php

namespace App\Transformers;

use App\Incident;
use League\Fractal;

class NoteTransformer extends Fractal\TransformerAbstract {

	public function transform(Incident $incident)
	{
		$actor = $incident->system->actor;
		return [
			'@context' => [
				'https://www.w3.org/ns/activitystreams',
				'https://w3id.org/security/v1',
			],
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
		];
	}

}