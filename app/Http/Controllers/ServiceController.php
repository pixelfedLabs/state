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

	public function show(Request $request, $id, $slug)
	{
		$service = Service::whereSlug($slug)->whereActive(true)->findOrFail($id);
		return view('service.show', compact('service'));
	}
}
