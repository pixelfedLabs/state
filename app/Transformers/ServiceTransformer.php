<?php

namespace App\Transformers;

use App\Service;
use League\Fractal;

class ServiceTransformer extends Fractal\TransformerAbstract {

	public function transform(Service $service)
	{
		return [
			'name' => $service->name,
			'url' => $service->url(),
			'domain' => parse_url($service->url(), PHP_URL_HOST),
			'description' => $service->description,
			'tooltip' => $service->tooltip,
			'active' => (bool) $service->active,
			'state' => 'ok',
			'agent' => $service->agents->count() > 0 ? $service->agents->first()->slug : null
		];
	}

}