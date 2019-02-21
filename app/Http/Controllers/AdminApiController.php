<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
	Actor,
	Agent,
	AgentCheck,
	Incident,
	IncidentUpdate,
	Service,
	System
};
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\{
	AgentTransformer,
	IncidentTransformer,
	SystemTransformer,
};

class AdminApiController extends Controller
{
	protected $fractal;

	public function __construct()
	{
		$this->middleware('auth');
		$this->fractal = new Fractal\Manager();
		$this->fractal->setSerializer(new ArraySerializer());
	}

	public function agents(Request $request)
	{
		$agents = Agent::orderByDesc('id')->paginate(10);
		$res = new Fractal\Resource\Collection($agents, new AgentTransformer());
		return $this->fractal->createData($res)->toArray();
	}
}
