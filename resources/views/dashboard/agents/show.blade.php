@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="d-flex align-items-center justify-content-between mb-0">
	<div>
		<p class="h3 font-nunito font-weight-bold mb-0">Agent</p>
		<p class="lead font-nunito mb-0">Agents perform uptime monitoring for Systems and Services</p>
	</div>
	<div>
		<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.agents')}}"><i class="fas fa-chevron-left"></i> Back</a>
	</div>
</div>
<hr>

<div class="row">
	<div class="col-12">
		<div class="pb-4">
			<uptime-graph id="{{$agent->id}}"></uptime-graph>
		</div>
	</div>
	<div class="col-12 col-md-6 mb-3">
		<div class="card">
			<div class="card-header bg-light font-nunito font-weight-bold">History</div>
			<ul class="list-group list-group-flush">
				@foreach($checks as $check)
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<div>
						<a class="font-weight-bold mr-3" href="{{$check->url()}}">{{$check->id}}</a>
						<span class="text-muted font-weight-lighter">Uptime Check</span>
					</div>
					<div>
						<span class="font-weight-lighter small mr-3">{{$check->created_at->diffForHumans(null, true,true,true)}}</span>
						@if($check->online)
							<span class="badge badge-success">{{$check->response_code}}</span>
						@else
							<span class="badge badge-danger">{{$check->response_code}}</span>
						@endif
					</div>
				</li>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="col-12 col-md-6 mb-3">
		<div class="card">
			<div class="card-body">
				<form method="post">
					@csrf
					<div class="form-group">
						<label class="font-nunito">Service</label>
						<select class="custom-select" name="service">
							<option disabled>Select a System Service</option>
							@foreach(App\Service::get() as $service)
							<option value="{{$service->id}}" {{$service->id == $agent->system_id?'checked':''}}>Service: {{$service->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group pb-2">
						<label class="font-nunito">Name</label>
						<input type="text" class="form-control" name="name" placeholder="Agent Name (ex: Website)" autocomplete="off" value="{{$agent->name}}">
					</div>
					{{-- <div class="form-group pb-4">
						<label class="font-nunito">Description</label>
						<textarea class="form-control" name="description" placeholder="Optional description of the agent" rows="4">{{$agent->description}}</textarea>
					</div> --}}
					<div class="form-group pb-2">
						<label class="font-nunito">Monitor URL</label>
						<input type="text" class="form-control" name="check_url" placeholder="https://mywebsite.test/health-check" autocomplete="off" value="{{$agent->check_url}}">
					</div>
					<div class="form-group pb-2">
						<label class="font-nunito">Monitor Text</label>
						<input type="text" class="form-control" name="check_text" placeholder="Hi, we're online and ready to help!" autocomplete="off" value="{{$agent->check_text}}">
						<p class="form-text small text-muted mb-0">Optional text or phrase on the page to detect if it's online.</p>
					</div>
					<div class="form-group pb-0">
						<label class="font-nunito">Check Frequency</label>
						<input type="range" class="custom-range" name="frequency" id="freq" min="5" max="60" step="5" value="{{$agent->frequency}}">
						<p class="form-text small text-muted">
							<span>Check every: </span>
							<span id="freqLabel" class="font-nunito font-weight-bold">{{$agent->frequency}} minutes</span>
						</p>
					</div>
					<div class="form-group pb-0">
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" name="active" id="isActive" {{$agent->active?'checked':''}}>
							<label class="custom-control-label" for="isActive">
								<span class="font-nunito font-weight-bold">Active</span>
							</label>
						</div>
					</div>		
					<hr>
					<div class="form-group text-right mb-0">
						<button type="submit" class="btn btn-primary font-nunito font-weight-bold">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="float-right">
	<form method="post">
		<input type="hidden" name="_method" value="DELETE">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<button type="submit" class="btn btn-danger btn-sm font-nunito font-weight-bold py-1">Delete Agent</button>
	</form>
</div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
	$('#freq').on('change', function() {
		let el = $(this);
		let val = el.val();
		let label = $('#freqLabel');
		label.text(val + ' minutes');
	});
</script>
@endpush