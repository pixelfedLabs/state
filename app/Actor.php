<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
	public function url()
	{
		return url("/account/{$this->username}");
	}

	public function emailUrl()
	{
		return "@{$this->username}@".parse_url($this->url(), PHP_URL_HOST);
	}

	public function service()
	{
		return $this->hasOne(Service::class, 'id');
	}

	public function permalink($suffix = null)
	{
		return $this->url() . $suffix;
	}

	public function followers()
	{
		return $this->hasMany(Follower::class);
	}

	public function incidents()
	{
		return $this->hasMany(Incident::class, 'system_id', 'system_id');
	}

	public function keyId()
	{
		return $this->permalink('#main-key');
	}
}
