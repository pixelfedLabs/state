<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\{
	Incident,
	IncidentUpdate,
	Service,
	System
};

class DashboardController extends Controller
{
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

	public function incidents()
	{
		$incidents = Incident::orderByDesc('id')->paginate(10);
		return view('dashboard.incidents.home', compact('incidents'));
	}

	public function incidentShow(Request $request, $id)
	{
		$incident = Incident::with('service')->findOrFail($id);
		return view('dashboard.incidents.show', compact('incident'));
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
		$incident->title = $title;
		$incident->save();
		return $incident->url();
	}
}
