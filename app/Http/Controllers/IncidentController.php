<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incident;
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\{
	NoteTransformer
};

class IncidentController extends Controller
{
    public function show(Request $request, $id)
    {
    	$incident = Incident::with('service')->whereSlug($id)->firstOrFail();
    	if($request->wantsJson()) {
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ArraySerializer());
			$res = new Fractal\Resource\Item($incident, new NoteTransformer);
    		return response()->json($fractal->createData($res)->toArray(), 200, [
				'Content-Type' => 'application/activity+json; charset=utf-8'
			]);
    	} else {
    		return view('incident.show', compact('incident'));
    	}
    }

    public function showPaginated(Request $request)
    {
        return view('incident.previous');
    }
}
