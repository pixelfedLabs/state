@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="title mb-0">
	<p class="h3 font-nunito font-weight-bold mb-0">{{$service->name}}</p>
	<p class="lead font-nunito mb-0">ID: {{$service->id}}</p>
</div>
<hr>

<div class="row">
	<div class="col-12 col-md-6">
		<div class="card">
			<div class="card-header bg-light lead text-center font-nunito font-weight-bold">Incidents</div>
			<div class="list-group list-group-flush" style="max-height: 400px;overflow-y: auto;">
			@foreach($service->incidents as $incident)
				<div class="list-group-item">
					<div class="d-flex justify-content-between">
						<div>
							<p class=" font-nunito font-weight-bold mb-0">
								ID: {{$incident->id}}
							</p>
							<p class=" font-nunito font-weight-bold mb-0">
								System: <a class="font-nunito font-weight-bold" href="{{$incident->system->dashboardUrl()}}">{{str_limit($incident->system->name,18)}}</a>
							</p>
							<p class=" font-nunito font-weight-bold mb-0">
								Service: <a class="font-nunito font-weight-bold" href="{{$incident->service->dashboardUrl()}}">{{str_limit($incident->service->name,18)}}</a>
							</p>
							<p class=" font-nunito font-weight-bold mb-0">
								State: <span class="font-nunito font-weight-bold">{{$incident->state}}</span>
							</p>
							<p class="mb-0">{{$incident->title}}</p>
						</div>
						<div>
							<a class="btn btn-outline-secondary font-nunito btn-sm py-0" href="{{$incident->dashboardUrl()}}">View</a>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
</div>
</div>
@endsection