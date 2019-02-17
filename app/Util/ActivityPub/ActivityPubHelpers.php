<?php

namespace App\Util\ActivityPub;

use App\Actor;
use Zttp\Zttp;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ActivityPubHelpers {

	public static function validateUrl($url)
	{
		$localhosts = [ '127.0.0.1', 'localhost', '::1'];
		$valid = filter_var($url, FILTER_VALIDATE_URL);
		if(in_array(parse_url($valid, PHP_URL_HOST), $localhosts) || !$valid) {
			return false;
		}
		return $valid;
	}

	public static function validateLocalUrl($url)
	{
		$url = self::validateUrl($url);
		if($url) {
			$domain = parse_url(config('app.url'), PHP_URL_HOST);
			$host = parse_url($url, PHP_URL_HOST);
			$url = $domain === $host ? $url : false;
			return $url;
		}
		return false;
	}

	public static function zttpUserAgent()
	{
		return [
			'Accept'     => 'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
			'User-Agent' => 'PixelfedStateBot - https://github.com/dansup/state',
		];
	}

	public static function fetchFromUrl($url)
	{
		abort_if(self::validateUrl($url) == false, 403);
		$res = Zttp::withHeaders(self::zttpUserAgent())->get($url);
		$res = json_decode($res->body(), true, 8);
		if(json_last_error() == JSON_ERROR_NONE) {
			return $res;
		} else {
			return false;
		}
	}
}