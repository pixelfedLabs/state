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
    	return $this->belongsTo(System::class);
    }
}
