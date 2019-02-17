<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function system()
    {
    	return $this->belongsTo(System::class);
    }

    public function actor()
    {
    	return $this->belongsTo(Actor::class);
    }
}
