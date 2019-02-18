<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incident;

class IncidentController extends Controller
{
    public function show(Request $request, $id)
    {
    	$incident = Incident::with('system')->whereSlug($id)->firstOrFail();
    	return view('incident.show', compact('incident'));
    }
}
