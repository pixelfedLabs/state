<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
	public function dashboardUrl()
	{
		return url("/dashboard/systems/show/{$this->id}");
	}

	public function services()
	{
		return $this->hasMany(Service::class);		
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
