<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentUpdate extends Model
{
	public function incident()
	{
		return $this->belongsTo(Incident::class);
	}

	public function dashboardUrl()
	{
		return url("/dashboard/incidents/show/{$this->incident->id}/update/{$this->id}");
	}
}
