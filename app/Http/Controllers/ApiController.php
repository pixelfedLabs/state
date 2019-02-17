<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
	Incident,
	IncidentUpdate,
	Service,
	System
};
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\{
	SystemTransformer
};

class ApiController extends Controller
{
	protected $fractal;

	public function __construct()
	{
		$this->fractal = new Fractal\Manager();
		$this->fractal->setSerializer(new ArraySerializer());
	}

	public function systems(Request $request)
	{
		$systems = System::orderByDesc('created_at')->paginate(10);
		$res = new Fractal\Resource\Collection($systems, new SystemTransformer());
		return $this->fractal->createData($res)->toArray();
	}
}
