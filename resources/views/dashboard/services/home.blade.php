@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="title mb-0">
	<p class="h3 font-nunito font-weight-bold mb-0">Services</p>
	<p class="lead font-nunito mb-0">Manage Services</p>
</div>
<hr>

<ul class="list-group">
@foreach($services as $service)
<li class="list-group-item">
	<div class="d-flex justify-content-between">
		<div>
			<span class="text-primary font-nunito font-weight-bold">{{$service->id}}</span>
			<span class="pl-2 font-nunito font-weight-bold">{{$service->name}}</span>
		</div>
		<div>
			<a class="btn btn-outline-secondary font-nunito btn-sm py-0" href="{{$service->dashboardUrl()}}">View</a>
		</div>
	</div>
</li>
@endforeach
</ul>

<div class="d-flex justify-content-center mt-5">
	{{$services->links()}}
</div>

</div>
@endsection