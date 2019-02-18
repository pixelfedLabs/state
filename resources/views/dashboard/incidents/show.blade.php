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
<div class="row">
	<div class="col-12 col-md-6">
		<div class="card">
			<div class="card-header bg-light lead text-center font-nunito font-weight-bold">Updates</div>
			<div class="list-group list-group-flush" style="max-height: 400px;overflow-y: auto;">
			@foreach($updates as $update)
				<div class="list-group-item">
					<div class="d-flex justify-content-between">
						<div>
							<p class=" font-nunito font-weight-bold mb-0">
								ID: {{$update->id}}
							</p>
							<p class=" font-nunito font-weight-bold mb-0">
								System: <a class="font-nunito font-weight-bold" href="{{$update->system->dashboardUrl()}}">{{str_limit($update->system->name,18)}}</a>
							</p>
							<p class=" font-nunito font-weight-bold mb-0">
								Service: <a class="font-nunito font-weight-bold" href="{{$update->service->dashboardUrl()}}">{{str_limit($update->service->name,18)}}</a>
							</p>
							<p class=" font-nunito font-weight-bold mb-0">
								State: <span class="font-nunito font-weight-bold">{{$update->state}}</span>
							</p>
							<p class="mb-0">{{$update->description}}</p>
						</div>
						<div>
							<a class="btn btn-outline-secondary font-nunito btn-sm py-0" href="{{$update->dashboardUrl()}}">View</a>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
</div>
@endsection