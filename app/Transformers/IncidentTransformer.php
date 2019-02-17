<?php

namespace App\Transformers;

use App\Incident;
use League\Fractal;

class IncidentTransformer extends Fractal\TransformerAbstract {

	public function transform(Incident $incident)
	{
		return [
			'system_id' => $incident->system_id,
			'service_id' => $incident->service_id,
			'url' => $incident->url(),
			'title' => $incident->title,
			'state' => $incident->state,
			'resolved_at' => $incident->resolved_at
		];
	}

}