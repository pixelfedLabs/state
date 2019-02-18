<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\{
	Actor,
	Incident,
	IncidentUpdate,
	Service,
	System
};
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\{
	IncidentTransformer,
	SystemTransformer,
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

	public function incidents(Request $request)
	{
		$incidents = collect([]);
		$periods = collect(\Carbon\CarbonPeriod::create(now()->subDays(14), now()));
		foreach($periods->reverse() as $period) {
			$day = $period->format('Y-m-d');
			$i = Incident::whereDate('created_at',$day)->get();
			$res = new Fractal\Resource\Collection($i, new IncidentTransformer());

			$incident = [
				'date' => $day,
				'incidents' => $this->fractal->createData($res)->toArray()
			];
			$incidents->push($incident);
		}

		return $incidents;
	}
}
