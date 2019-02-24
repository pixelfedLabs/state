<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\{
	Agent,
	AgentCheck,
	Incident,
	IncidentUpdate,
	Service,
	System
};

class DashboardController extends Controller
{
	protected $states = [
		'investigating',
		'update',
		'resolved'
	];

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function home()
	{
		$services = Service::orderByDesc('id')->paginate(10);
		$incidents = Incident::orderByDesc('id')->paginate(10);
		return view('dashboard.home', compact('services', 'incidents'));
	}

	public function services()
	{
		$services = Service::orderByDesc('id')->paginate(10);
		return view('dashboard.services.home', compact('services'));
	}

	public function serviceShow(Request $request, $id)
	{
		$service = Service::findOrFail($id);
		return view('dashboard.services.show', compact('service'));
	}

	public function serviceCreate(Request $request)
	{
		return view('dashboard.services.create');
	}

	public function serviceStore(Request $request)
	{
		$this->validate($request, [
			'name'	=> 'required|string|max:40',
			'description' => 'nullable|string|max:150',
			'active' => 'required'
		]);

		$name = $request->input('name');
		$description = $request->input('description');
		$active = $request->input('active') == 'on';

		$service = new Service;
		$service->name = $name;
		$service->slug = str_slug($name);
		$service->description = $description;
		$service->tooltip = $description;
		$service->active = $active;
		$service->save();

		return redirect($service->dashboardUrl());
	}

	public function serviceDelete(Request $request, $id)
	{
		$service = Service::with('updates','incidents')->findOrFail($id);

		$service->updates()->delete();
		$service->incidents()->delete();
		$service->delete();
		
		return redirect('/dashboard');
	}

	public function incidents()
	{
		$incidents = Incident::orderByDesc('id')->paginate(10);
		return view('dashboard.incidents.home', compact('incidents'));
	}

	public function incidentShow(Request $request, $id)
	{
		$incident = Incident::with('service')->findOrFail($id);
		$updates = $incident->updates()->orderByDesc('id')->paginate(10);
		return view('dashboard.incidents.show', compact('incident', 'updates'));
	}

	public function incidentCreate(Request $request)
	{
		$services = Service::get();
		return view('dashboard.incidents.create', compact('services'));
	}

	public function incidentStore(Request $request)
	{
		$this->validate($request, [
			'service' => 'required|integer|exists:services,id',
			'title' => 'nullable|max:150'
		]);
		$service = Service::findOrFail($request->input('service'));
		$title = $request->input('title');

		$incident = new Incident;
		$incident->service_id = $service->id;
		$incident->slug = (string) Str::uuid();
		$incident->state = 'investigating';
		$incident->title = $title;
		$incident->save();

		$update = new IncidentUpdate;
		$update->service_id = $service->id;
		$update->incident_id = $incident->id;
		$update->slug = (string) Str::uuid();
		$update->state = 'investigating';
		$update->description = 'We are investigating a potential issue with this service.';
		$update->save();

		return redirect($incident->dashboardUrl());
	}

	public function incidentDelete(Request $request, $id)
	{
		$incident = Incident::with('updates')->findOrFail($id);
		foreach($incident->updates as $update) {
			$update->delete();
		}
		$incident->delete();
		return redirect(route('dashboard.incidents'));
	}

	public function incidentStatusStore(Request $request, $id)
	{
		$this->validate($request, [
			'description' => 'required|string|min:1',
			'state' => [
				'required',
				'string',
				Rule::in($this->states)
			]
		]);

		$incident = Incident::findOrFail($id);
		$service = $incident->service;

		$update = new IncidentUpdate;
		$update->service_id = $service->id;
		$update->incident_id = $incident->id;
		$update->slug = (string) Str::uuid();
		$update->description = $request->input('description');
		$update->state = $request->input('state') ?? 'investigating';
		$update->save();

		if($request->input('state') == 'resolved') {
			$incident->state = 'resolved';
			$incident->resolved_at = now();
			$incident->save();
		}

		return redirect($incident->dashboardUrl());
	}

	public function incidentUpdateShow(Request $request, $incidentId, $updateId)
	{
		$incident = Incident::findOrFail($incidentId);
		$update = $incident->updates()->findOrFail($updateId);
		$services = Service::get();
		return view('dashboard.incidents.updates.show', compact('incident', 'update', 'services'));
	}

	public function incidentUpdateStore(Request $request, $incidentId, $updateId)
	{
		$this->validate($request, [
			'description' => 'required|string|min:1',
			'state' => [
				'required',
				'string',
				Rule::in($this->states)
			]
		]);

		$incident = Incident::findOrFail($incidentId);
		$update = $incident->updates()->findOrFail($updateId);
		$update->description = $request->input('description');
		$update->state = $request->input('state') ?? 'investigating';
		$update->save();

		return redirect($update->dashboardUrl());
	}

	public function incidentUpdateDelete(Request $request, $incidentId, $updateId)
	{
		$incident = Incident::findOrFail($incidentId);
		$update = $incident->updates()->findOrFail($updateId);
		$update->delete();
		return redirect($incident->dashboardUrl());
	}

	public function agents(Request $request)
	{
		$agents = Agent::orderByDesc('id')->paginate(10);
		return view('dashboard.agents.home', compact('agents'));
	}

	public function agentCreate(Request $request)
	{
		return view('dashboard.agents.create');
	}

	public function agentStore(Request $request)
	{
		$this->validate($request, [
			'service' => 'required|integer|exists:services,id',
			'name' => 'nullable|string|max:40',
			'description' => 'nullable|string|max:150',
			'check_url' => 'required|url|unique:agents',
			'check_text' => 'nullable|string|max:150',
			'frequency' => 'required|integer|min:5|max:60',
			'active' => 'nullable|string'
		]);

		$service = Service::findOrFail($request->input('service'));

		$agent = new Agent;
		$agent->slug = (string) Str::uuid();
		$agent->service_id = $service->id;
		$agent->name = $request->input('name');
		$agent->description = $request->input('description');
		$agent->check_url = $request->input('check_url');
		$agent->check_text = $request->input('check_text');
		$agent->frequency = $request->input('frequency');
		$agent->active = $request->input('active') == 'on';
		$agent->save();

		return redirect($agent->url());
	}

	public function agentShow(Request $request, $id)
	{
		$agent = Agent::findOrFail($id);
		$checks = $agent->checks()->orderByDesc('id')->take(8)->get();
		return view('dashboard.agents.show', compact('agent', 'checks'));
	}

	public function agentUpdate(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'nullable|string|max:40',
			'description' => 'nullable|string|max:150',
			'check_url' => 'required|url|unique:agents',
			'check_text' => 'nullable|string|max:150',
			'frequency' => 'required|integer|min:5|max:60',
			'active' => 'nullable|string'
		]);

		$agent = Agent::findOrFail($id);
		$agent->slug = (string) Str::uuid();
		$agent->service_id = $service->id;
		$agent->name = $request->input('name');
		$agent->description = $request->input('description');
		$agent->check_url = $request->input('check_url');
		$agent->check_text = $request->input('check_text');
		$agent->frequency = $request->input('frequency');
		$agent->active = $request->input('active') == 'on';
		$agent->save();

		return redirect($agent->url());
	}

	public function agentDelete(Request $request, $id)
	{
		$agent = Agent::findOrFail($id);
		$agent->checks()->delete();
		$agent->delete();

		return redirect(route('dashboard.agents'));
	}

	public function agentCheckShow(Request $request, $agent_id, $check_id)
	{
		$agent = Agent::findOrFail($agent_id);
		$check = AgentCheck::findOrFail($check_id);
		$headers = $check->headers ? json_decode($check->headers, true) : [];
		return view('dashboard.agents.check_show', compact('check', 'agent', 'headers'));
	}
}
