<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentCheck extends Model
{
	protected $fillable = ['*'];

	public function system()
	{
		return $this->belongsTo(System::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function agent()
	{
		return $this->belongsTo(Agent::class);
	}
}
