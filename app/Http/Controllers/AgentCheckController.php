<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
	Agent,
	AgentCheck
};

class AgentCheckController extends Controller
{
    public function show(Request $request, $slug, $year, $month, $day)
    {
    	$agent = Agent::whereSlug($slug)->firstOrFail();

    	$date = "{$year}-{$month}-{$day}";
    	$checks = AgentCheck::whereAgentId($agent->id)
    		->whereDate('created_at', $date)
    		->count();
    	abort_if($checks == 0, 404);
    	return view('uptime.show', compact('agent', 'checks', 'slug', 'year', 'month','day', 'date'));
    }
}
