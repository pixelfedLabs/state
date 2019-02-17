<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
	public function about()
	{
		return view('site.about');
	}
	
	public function subscribe()
	{
		return view('site.subscribe');
	}
}
