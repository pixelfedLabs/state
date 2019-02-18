<?php

namespace App\Transformers;

use App\IncidentUpdate;
use League\Fractal;

class IncidentUpdateTransformer extends Fractal\TransformerAbstract {

	public function transform(IncidentUpdate $incident)
	{
		return [
			'system_id' => $incident->system_id,
			'service_id' => $incident->service_id,
			'incident_id' => $incident->incident_id,
			'state' => $incident->getState(),
			'description' => $incident->description,
			'url' => $incident->url(),
			'created_at' => $incident->created_at->format(DATE_RFC850)
		];
	}

}