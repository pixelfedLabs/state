<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
		return view('dashboard.services.home');
	}


	public function serviceShow(Request $request, $id)
	{
		$service = Service::findOrFail($id);
		return view('dashboard.services.show', compact('service'));
	}

	public function incidents()
	{
		return view('dashboard.incidents.home');
	}
}
