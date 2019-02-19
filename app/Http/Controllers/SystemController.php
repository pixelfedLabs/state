<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function atomFeed()
    {
    	$entries = [];
    	return response()->view('system.atom', compact('entries'))->header('Content-Type', 'application/atom+xml');
    }
}
