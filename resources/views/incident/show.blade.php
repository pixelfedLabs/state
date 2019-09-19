@extends('layouts.app')

@section('content')
<div class="container">
	
	<div class="title text-center py-5">
		<p class="h1 font-nunito font-weight-bold">Incident on {{$incident->created_at}} UTC</p>
		<p class="h4 font-nunito text-muted">Incident Report for {{$incident->service->name}}</p>
	</div>

	<div class="incidents-list">
		@foreach($incident->updates()->latest()->get() as $update)
		<div class="row my-3">
			<div class="col-12 col-md-3 h4 font-weight-bold">{{$update->getState()}}</div>
			<div class="col-12 col-md-9">
				<p class="mb-0 lead">{{$update->description}}</p>
				<p class="text-muted">Posted {{$update->created_at->diffForHumans()}}. {{$update->created_at->format(DATE_RFC850)}}</p>
			</div>
		</div>
		@endforeach
	</div>

	<div class="col-12 incidents-footer"></div>

	<p class="py-3">
		<a href="/"><i class="fas fa-chevron-left fa-sm"></i> Current Status</a>
	</p>
</div>
@endsection