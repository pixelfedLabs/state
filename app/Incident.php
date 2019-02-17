<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
	public function url()
	{
		return url("/incident/{$this->slug}");
	}
	
	public function dashboardUrl()
	{
		return url("/dashboard/incidents/show/{$this->id}");
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function system()
	{
		return $this->belongsTo(System::class);
	}
}
