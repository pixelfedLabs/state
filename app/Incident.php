<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Incident extends Model
{
	public function url()
	{
		return url("/incident/{$this->slug}");
	}

	public function dayUrl()
	{
		$year = $this->created_at->format('Y');
		$month = $this->created_at->format('m');
		$day = $this->created_at->format('d');
		$service = $this->service->id;
		$slug = $this->service->slug;
		return url("/history/{$service}/{$slug}/{$year}/{$month}/{$day}");
	}

	public function permalink($suffix = null)
	{
		return $this->url() . $suffix;
	}

	public function dashboardUrl()
	{
		return url("/dashboard/incidents/show/{$this->id}");
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function updates()
	{
		return $this->hasMany(IncidentUpdate::class);
	}

	public function getState()
	{
		switch ($this->state) {
			case 'investigating':
				return 'Investigating';
				break;

			case 'update':
				return 'Update';
				break;


			case 'resolved':
				return 'Resolved';
				break;
			
			default:
				return $this->state;
				break;
		}
	}

	public function atomTag()
	{
		$domain = parse_url(config('app.url'), PHP_URL_HOST);
		return "tag:{$domain},2005:Incident/{$this->id}";
	}
}
