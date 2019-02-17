<?php

namespace App\Transformers;

use App\Service;
use League\Fractal;

class ServiceTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = ['incidents'];

	public function transform(Service $service)
	{
		return [
			'system_id' => $service->system_id,
			'name' => $service->name,
			'url' => $service->url(),
			'description' => $service->description,
			'tooltip' => $service->tooltip,
			'active' => (bool) $service->active 
		];
	}

	public function includeIncidents(Service $service)
	{
		$incidents = $service->incidents;
		return $this->collection($incidents, new IncidentTransformer);
	}
}