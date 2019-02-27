<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use \Carbon\Carbon;
use App\{
	Agent,
	AgentCheck,
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
	ServiceTransformer,
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
		return [];
	}


	public function services(Request $request)
	{
		return Cache::remember('api-v1:services', now()->addMinutes(1440), function() {
			$service = Service::orderByDesc('created_at')->get();
			$res = new Fractal\Resource\Collection($service, new ServiceTransformer());
			return $this->fractal->createData($res)->toArray();
		});
	}

	public function service(Request $request, $id)
	{
		$service = Service::findOrFail($id);
		$res = new Fractal\Resource\Item($service, new ServiceTransformer());
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

	public function serviceUptime(Request $request, $agentId)
	{
		$this->validate($request, [
			'days' => 'nullable|integer|min:30|max:90',
		]);
		$agent = Agent::whereActive(true)->whereSlug($agentId)->firstOrFail();
		$days = $request->input('days') ?? 90;
		$res = Cache::remember('api:agent:uptime:id-'.$agent->id.':days-'.$days, now()->addMinutes(15), function() use($agent, $days) {
			$incidents = collect([]);
			$periods = collect(\Carbon\CarbonPeriod::create(now()->subDays($days), now()));
			foreach($periods as $i => $period) {
				$day = $period->format('Y-m-d');
				$checks = AgentCheck::whereAgentId($agent->id)
					->whereDate('created_at', $day)
					->count();
				$count = AgentCheck::whereAgentId($agent->id)
					->whereDate('created_at', $day)
					->whereOnline(false)
					->count();
				$frequency = $agent->frequency;
				$url = '/uptime/'.$agent->slug.'/'.$period->format('Y').'/'.$period->format('m').'/'.$period->format('d');
				if($checks == 0) {
					$class = 'ug state-nodata';
					$downtime = 0;
					$uptime_percent = 0;
					$url = null;
				} else {
					$class = 'ug';
					$downtime = $count > 1 ? $count * $frequency : 0;
					$uptime_percent = "100";
				}
				if($downtime != 0) {
					$uptime_percent = ((1440 - $downtime) / 1440) * 100;
					$class = 'ug state-degraded';
					$p = floor($uptime_percent);
					if($p < 96) {
						$class = 'ug state-outage';
					}
				}
				$incidents->push([
					'daysAgo' => $days--,
					'url' => $url,
					'state' => 'ok',
					'date' => $day,
					'class' => $class,
					'downtime_minutes' => $downtime,
					'uptime_percent' => number_format($uptime_percent,0)
				]);
			}

			return $incidents->reverse();
		});
		return response()->json($res);
	}
}
