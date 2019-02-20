<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    public function url()
    {
    	return $this->dashboardUrl();
    }

	public function dashboardUrl()
	{
		return url("/dashboard/agents/show/{$this->id}");
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function system()
	{
		return $this->belongsTo(System::class);
	}

	public function humanFrequency()
	{
		return $this->frequency . ' minutes';
	}

	public function checks()
	{
		return $this->hasMany(AgentCheck::class);
	}
}
