<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function service()
    {
    	return $this->belongsTo(Service::class);
    }

    public function actor()
    {
    	return $this->belongsTo(Actor::class);
    }
}
