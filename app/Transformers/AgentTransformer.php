<?php

namespace App\Transformers;

use App\Agent;
use League\Fractal;

class AgentTransformer extends Fractal\TransformerAbstract {

	public function transform(Agent $agent)
	{
		return [
			'id' => $agent->id,
			'url' => $agent->url(),
			'name' => $agent->name,
			'description' => $agent->description,
			'slug' => $agent->slug,
			'local' => (bool) $agent->local,
			'frequency' => $agent->frequency,
			'check_url' => $agent->check_url,
			'check_text' => $agent->check_text,
			'active' => (bool) $agent->active
		];
	}

}