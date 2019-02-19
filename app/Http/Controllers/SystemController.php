<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\System;

class SystemController extends Controller
{
    public function atomFeed()
    {
    	$entries = System::first()->incidents()->orderByDesc('id')->take(10)->get();
    	return response()->view('system.atom', compact('entries'))->header('Content-Type', 'application/atom+xml');
    }
}
