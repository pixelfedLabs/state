<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actor;
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\{
	ActivityPubActorTransformer
};

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
		$res = new Fractal\Resource\Item($actor, new ActivityPubActorTransformer());
		return response()->json($this->fractal->createData($res)->toArray(), 200, [], JSON_PRETTY_PRINT);
	}

    public function inbox(Request $request)
    {

    }

    public function outbox(Request $request, $id)
    {
    	$actor = Actor::whereUsername($id)->firstOrFail();
    	return $actor;
    }
}
