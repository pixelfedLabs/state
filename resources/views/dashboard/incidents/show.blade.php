@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="d-flex align-items-center justify-content-between mb-0">
	<div>
		<p class="h3 font-nunito font-weight-bold mb-0">Incident</p>
		<p class="lead font-nunito mb-0">Incident # {{$incident->id}}</p>
	</div>
	<div>
		<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.incidents')}}"><i class="fas fa-chevron-left mr-2"></i>Back</a>
		<a class="btn btn-primary font-nunito font-weight-bold" href="{{$incident->url()}}">View</a>
	</div>
</div>
<hr>

@endsection