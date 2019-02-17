<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
	public function dashboardUrl()
	{
		return url("/dashboard/systems/show/{$this->id}");
	}
}
