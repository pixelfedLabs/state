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

	public function url()
	{
		return $this->incident->url();
	}

	public function getState()
	{
		switch ($this->state) {
			case 'investigating':
				return 'Investigating';
				break;
			
			default:
				return $this->state;
				break;
		}
	}
}
