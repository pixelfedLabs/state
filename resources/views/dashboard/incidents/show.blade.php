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
	<div class="col-12 col-md-6 mb-4">
		<div class="card">
			<div class="card-header bg-light lead text-center font-nunito font-weight-bold">
				Updates 
				<span class="badge badge-primary rounded-circle" style="vertical-align: text-top;">{{$incident->updates()->count()}}</span>
			</div>
			<div class="list-group list-group-flush" style="height: 420px;overflow-y: auto;">
			@if($updates->count() > 0)
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
			@else
			<div class="h-100 d-flex align-items-center justify-content-center">
				<p class="mb-0 font-nunito text-muted">There are no updates for this incident.</p>
			</div>
			@endif
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6">
		<ul class="list-group list-group-horizontal-lg mb-3">
			<li class="list-group-item text-center">
				<p class="lead font-nunito font-weight-bold mb-0">{{str_limit($incident->system->name,11)}}</p>
				<p class="small text-muted mb-0">SYSTEM</p>
			</li>
			<li class="list-group-item text-center">
				<p class="lead font-nunito font-weight-bold mb-0">{{str_limit($incident->service->name,10)}}</p>
				<p class="small text-muted mb-0">SERVICE</p>
			</li>
			<li class="list-group-item text-center">
				<p class="lead font-nunito font-weight-bold mb-0">{{$incident->getState()}}</p>
				<p class="small text-muted mb-0">STATUS</p>
			</li>
		</ul>

		<div class="card">
			<form method="post">
				@csrf
				<div class="card-header bg-white lead font-nunito font-weight-bold">Status Update</div>
				<div class="card-body">
					<div class="form-group">
						<label class="font-nunito">Message</label>
						<textarea class="form-control" name="description" rows="4" placeholder="Add a status update message to this incident"></textarea>
					</div>
					<div class="form-group">
						<label class="font-nunito">Status</label>
						<select class="custom-select" name="state">
							<option value="investigating" {{$incident->state =='investigating'?'selected':''}}>Investigating</option>
							<option value="update" {{$incident->state =='update'?'selected':''}}>Update</option>
							<option value="resolved" {{$incident->state =='resolved'?'selected':''}}>Resolved</option>
						</select>
					</div>
				</div>
				<div class="card-footer text-right bg-white">
					<button type="submit" class="btn btn-primary btn-sm py-1 font-nunito font-weight-bold">Post</button>
				</div>
			</form>
		</div>
	</div>
</div>
<hr>
<div class="float-right">
	<form method="post">
		<input type="hidden" name="_method" value="DELETE">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<button type="submit" class="btn btn-danger btn-sm font-nunito font-weight-bold py-1">Delete Incident</button>
	</form>
</div>
@endsection