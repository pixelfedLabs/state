<?php

namespace App\Util\ActivityPub;

use App\Actor;
use Zttp\Zttp;
use Carbon\Carbon;
use GuzzleHttp\Client;
use \DateTime;

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

	public static function sign(Actor $actor, $url, $body = false, $addlHeaders = []) {
		if($body) {
			$digest = self::_digest($body);
		}
		$headers = self::_headersToSign($url, $body ? $digest : false);
		$headers = array_merge($headers, $addlHeaders);
		$stringToSign = self::_headersToSigningString($headers);
		$signedHeaders = implode(' ', array_map('strtolower', array_keys($headers)));
		$key = openssl_pkey_get_private($actor->private_key);
		openssl_sign($stringToSign, $signature, $key, OPENSSL_ALGO_SHA256);
		$signature = base64_encode($signature);
		$signatureHeader = 'keyId="'.$actor->keyId().'",headers="'.$signedHeaders.'",algorithm="rsa-sha256",signature="'.$signature.'"';
		unset($headers['(request-target)']);
		$headers['Signature'] = $signatureHeader;
		return self::_headersToCurlArray($headers);
	}

	public static function parseSignatureHeader($signature) {
		$parts = explode(',', $signature);
		$signatureData = [];
		foreach($parts as $part) {
			if(preg_match('/(.+)="(.+)"/', $part, $match)) {
				$signatureData[$match[1]] = $match[2];
			}
		}
		if(!isset($signatureData['keyId'])) {
			return [
				'error' => 'No keyId was found in the signature header. Found: '.implode(', ', array_keys($signatureData))
			];
		}
		if(!filter_var($signatureData['keyId'], FILTER_VALIDATE_URL)) {
			return [
				'error' => 'keyId is not a URL: '.$signatureData['keyId']
			];
		}
		if(!isset($signatureData['headers']) || !isset($signatureData['signature'])) {
			return [
				'error' => 'Signature is missing headers or signature parts'
			];
		}
		return $signatureData;
	}

	public static function verify($publicKey, $signatureData, $inputHeaders, $path, $body) {
		$digest = 'SHA-256='.base64_encode(hash('sha256', $body, true));
		$headersToSign = [];
		foreach(explode(' ',$signatureData['headers']) as $h) {
			if($h == '(request-target)') {
				$headersToSign[$h] = 'post '.$path;
			} elseif($h == 'digest') {
				$headersToSign[$h] = $digest;
			} elseif(isset($inputHeaders[$h][0])) {
				$headersToSign[$h] = $inputHeaders[$h][0];
			}
		}
		$signingString = self::_headersToSigningString($headersToSign);
		$verified = openssl_verify($signingString, base64_decode($signatureData['signature']), $publicKey, OPENSSL_ALGO_SHA256);
		return [$verified, $signingString];
	}

	private static function _headersToSigningString($headers) {
		return implode("\n", array_map(function($k, $v){
			return strtolower($k).': '.$v;
		}, array_keys($headers), $headers));
	}

	private static function _headersToCurlArray($headers) {
		return array_map(function($k, $v){
			return "$k: $v";
		}, array_keys($headers), $headers);
	}

	private static function _digest($body) {
		if(is_array($body)) {
			$body = json_encode($body);
		}
		return base64_encode(hash('sha256', $body, true));
	}

	protected static function _headersToSign($url, $digest = false) {
		$date = new DateTime('UTC');
		$headers = [
			'(request-target)' => 'post '.parse_url($url, PHP_URL_PATH),
			'Date' => $date->format('D, d M Y H:i:s \G\M\T'),
			'Host' => parse_url($url, PHP_URL_HOST),
			'Accept' => 'application/activity+json, application/json',
			'Content-Type' => 'application/activity+json'
		];
		if($digest) {
			$headers['Digest'] = 'SHA-256='.$digest;
		}
		return $headers;
	}

	public static function sendSignedObject($senderProfile, $url, $body)
	{
		$payload = is_array($body) ? json_encode($body) : $body;
		$headers = self::sign($senderProfile, $url, $body);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$response = curl_exec($ch);
		return;
	}

	public static function normalizeProfileUrl($url)
	{
		if (starts_with($url, 'acct:')) {
			$url = str_replace('acct:', '', $url);
		}

		if (!str_contains($url, '@') && filter_var($url, FILTER_VALIDATE_URL)) {
			$parsed = parse_url($url);
			$username = str_replace(['/', '\\', '@'], '', $parsed['path']);

			return ['domain' => $parsed['host'], 'username' => $username];
		}
		$parts = explode('@', $url);
		$username = null;
		$domain = null;

		foreach ($parts as $part) {
			if (empty($part)) {
				continue;
			}
			if (str_contains($part, '.')) {
				$domain = filter_var($part, FILTER_VALIDATE_URL) ?
				parse_url($part, PHP_URL_HOST) :
				$part;
			} else {
				$username = $part;
			}
		}

		return ['domain' => $domain, 'username' => $username];
	}
}