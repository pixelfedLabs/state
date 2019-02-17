<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
	public function url()
	{
		return url("/account/{$this->username}");
	}

	public function system()
	{
		return $this->hasOne(System::class, 'id');
	}

	public function permalink($suffix = null)
	{
		return $this->url() . $suffix;
	}

	public function followers()
	{
		return $this->hasMany(Follower::class);
	}

	public function keyId()
	{
		return $this->permalink('#main-key');
	}
}
