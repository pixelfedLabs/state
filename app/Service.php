<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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
}
