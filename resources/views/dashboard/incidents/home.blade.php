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

@endsection