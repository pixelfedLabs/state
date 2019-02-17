<?php

namespace App\Transformers;

use App\System;
use League\Fractal;

class SystemTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = ['services'];

	public function transform(System $system)
	{
		return [
			'name' => $system->name,
			'description' => $system->description,
			'website' => $system->website,
			'domain' => parse_url($system->website, PHP_URL_HOST)
		];
	}

	public function includeServices(System $system)
	{
		$services = $system->services;
		return $this->collection($services, new ServiceTransformer);
	}

}