<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function dashboardUrl()
    {
    	return url("/dashboard/services/show/{$this->id}");
    }
}
