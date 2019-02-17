<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function home()
	{
		return view('dashboard.home');
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
