@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="d-flex align-items-center justify-content-between mb-0">
	<div>
		<p class="h3 font-nunito font-weight-bold mb-0">Incidents</p>
		<p class="lead font-nunito mb-0">Manage Incidents</p>
	</div>
	<div>
		<a class="btn btn-primary font-nunito font-weight-bold" href="{{route('dashboard.incidents.create')}}"><i class="fas fa-plus-circle mr-2"></i>New Incident</a>
	</div>
</div>
<hr>

<ul class="list-group">
@foreach($incidents as $incident)
<li class="list-group-item">
	<div class="d-flex justify-content-between">
		<div>
			<span class="text-primary font-nunito font-weight-bold">{{$incident->id}}</span>
			<span class="pl-2 font-nunito font-weight-bold text-truncate">{{$incident->title}}</span>
		</div>
		<div>
			<a class="btn btn-outline-secondary font-nunito btn-sm py-0" href="{{$incident->dashboardUrl()}}">View</a>
		</div>
	</div>
</li>
@endforeach
</ul>

<div class="d-flex justify-content-center mt-5">
	{{$incidents->links()}}
</div>
@endsection