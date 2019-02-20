<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	public function url()
	{
		return url("/service/{$this->id}/{$this->slug}");
	}

	public function uptimeUrl()
	{
		return url("/service/{$this->id}/{$this->slug}/uptime");
	}

	public function dashboardUrl()
	{
		return url("/dashboard/services/show/{$this->id}");
	}

	public function system()
	{
		return $this->belongsTo(System::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function incidents()
	{
		return $this->hasMany(Incident::class);
	}

	public function updates()
	{
		return $this->hasMany(IncidentUpdate::class);
	}
}
