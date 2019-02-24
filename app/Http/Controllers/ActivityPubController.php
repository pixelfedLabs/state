<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actor;
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\{
	ActivityPubActorTransformer,
	ActivityPubOutboxTransformer
};
use App\Jobs\InboxWorker;
use App\Util\ActivityPub\ActivityPubHelpers as AP;

class ActivityPubController extends Controller
{
	protected $fractal;

	public function __construct()
	{
		$this->fractal = new Fractal\Manager();
		$this->fractal->setSerializer(new ArraySerializer());
	}

	public function profile(Request $request, $id)
	{
		$actor = Actor::whereUsername($id)->firstOrFail();
		$res = new Fractal\Resource\Item($actor, new ActivityPubActorTransformer);
		return response()->json($this->fractal->createData($res)->toArray(), 200, [
			'Content-Type' => 'application/activity+json'
		], JSON_PRETTY_PRINT);
	}

	public function inbox(Request $request, $username)
	{
		$agent = Actor::whereUsername($username)->firstOrFail();
		$signature = $request->header('signature');
		if(!$signature) {
			abort(400, 'Missing signature');
		}
		InboxWorker::dispatchNow($agent, $request->headers->all(), $request->getContent(), $signature);
		return response()->json([], 200);
	}

	public function outbox(Request $request, $id)
	{
		$actor = Actor::whereUsername($id)->firstOrFail();
		$res = new Fractal\Resource\Item($actor, new ActivityPubOutboxTransformer);
		return response()->json($this->fractal->createData($res)->toArray(), 200, [
			'Content-Type' => 'application/activity+json; charset=utf-8'
		], JSON_PRETTY_PRINT);
	}

	public function webfinger(Request $request)
	{
		$this->validate($request, ['resource'=>'nullable|string|min:3|max:255']);
		$resource = $request->input('resource');
		if(!$resource) {
			return response('');
		}
		$hash = hash('sha256', $resource);
		$parsed = AP::normalizeProfileUrl($resource);
		$username = $parsed['username'];
		$actor = Actor::whereUsername($username)->firstOrFail();
		$links = [
			[
				'rel'  => 'self',
				'type' => 'application/activity+json',
				'href' => $actor->permalink(),
			]
		];
		$webfinger = [
			'subject' => $resource,
			'aliases' => [$actor->permalink()],
			'links' => $links
		];
		return response()->json($webfinger, 200, [], JSON_PRETTY_PRINT);
	}

}
