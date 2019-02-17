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
		$services = Service::orderByDesc('created_at')->paginate(10);
		$incidents = Incident::orderByDesc('created_at')->paginate(10);
		return view('dashboard.home', compact('services', 'incidents'));
	}

	public function systems()
	{
		return view('dashboard.systems.home');
	}

	public function services()
	{
		return view('dashboard.services.home');
	}

	public function incidents()
	{
		return view('dashboard.incidents.home');
	}
}
