<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    public function service()
    {
    	return $this->belongsTo(Service::class);
    }
}
