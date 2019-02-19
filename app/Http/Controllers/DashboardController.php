<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\{
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

	public function systems()
	{
		$systems = System::orderByDesc('id')->paginate(10);
		return view('dashboard.systems.home', compact('systems'));
	}

	public function systemShow(Request $request, $id)
	{
		$system = System::findOrFail($id);
		return view('dashboard.systems.show', compact('system'));
	}

	public function systemCreate()
	{
		return view('dashboard.systems.create');
	}

	public function systemStore(Request $request)
	{
		$this->validate($request, [
			'name'	=> 'required|string|max:40',
			'description' => 'nullable|string|max:150',
			'website'	=> 'nullable|url',
			'active' => 'nullable'
		]);

		$name = $request->input('name');
		$description = $request->input('description');
		$website = $request->input('website');
		$active = $request->input('active') == 'on';

		$system = new System;
		$system->name = $name;
		$system->description = $description;
		$system->website = $website;
		$system->active = $active;
		$system->save();

		return redirect($system->dashboardUrl());
	}

	public function systemDelete(Request $request, $id)
	{
		$system = System::with('updates','incidents','services')->findOrFail($id);

		$system->updates()->delete();
		$system->incidents()->delete();
		$system->services()->delete();
		$system->delete();

		return redirect('/dashboard');
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
			'system' => 'required|integer|min:1|exists:systems,id',
			'name'	=> 'required|string|max:40',
			'description' => 'nullable|string|max:150',
			'active' => 'required'
		]);

		$system = (int) $request->input('system');
		$name = $request->input('name');
		$description = $request->input('description');
		$active = $request->input('active') == 'on';

		$service = new Service;
		$service->system_id = $system;
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
		$services = Service::with('system')->get();
		return view('dashboard.incidents.create', compact('services'));
	}

	public function incidentStore(Request $request)
	{
		$this->validate($request, [
			'service' => 'required|integer|exists:services,id',
			'title' => 'nullable|max:150'
		]);
		$service = Service::with('system')->findOrFail($request->input('service'));
		$title = $request->input('title');

		$incident = new Incident;
		$incident->system_id = $service->system->id;
		$incident->service_id = $service->id;
		$incident->slug = (string) Str::uuid();
		$incident->state = 'investigating';
		$incident->title = $title;
		$incident->save();

		$update = new IncidentUpdate;
		$update->system_id = $service->system->id;
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
		$update->system_id = $service->system->id;
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
}
