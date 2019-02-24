<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;

class ServiceController extends Controller
{
	public function atomFeed()
	{
		$entries = Service::first()->incidents()->orderByDesc('id')->take(10)->get();
		return response()->view('service.atom', compact('entries'))->header('Content-Type', 'application/atom+xml');
	}
}
