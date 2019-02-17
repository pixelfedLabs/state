<?php

namespace App\Transformers;

use App\System;
use League\Fractal;

class SystemTransformer extends Fractal\TransformerAbstract {

	public function transform(System $system)
	{
		return [
			'name' => $system->name,
			'description' => $system->description,
			'website' => $system->website,
		];
	}

}