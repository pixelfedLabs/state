@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
	<div class="d-flex align-items-center justify-content-between mb-0">
		<div>
			<p class="h3 font-nunito font-weight-bold mb-0">New Agent</p>
			<p class="lead font-nunito mb-0">Creating a new Agent</p>
		</div>
		<div>
			<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.agents')}}"><i class="fas fa-chevron-left mr-2"></i>Back</a>
		</div>
	</div>
	<hr>
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul class="mb-0">
			@foreach ($errors->all() as $error)
			<li class="font-nunito font-weight-bold">{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<div>
		<form method="post">
			@csrf
			<div class="form-group">
				<label class="font-nunito">System Service</label>
				<select class="custom-select" name="service">
					<option disabled>Select a System Service</option>
					@foreach(App\Service::get() as $service)
					<option value="{{$service->id}}">System: {{$service->system->name}} | Service: {{$service->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group pb-4">
				<label class="font-nunito">Name</label>
				<input type="text" class="form-control" name="name" placeholder="Agent Name (ex: Website)" autocomplete="off">
			</div>
			<div class="form-group pb-4">
				<label class="font-nunito">Description</label>
				<textarea class="form-control" name="description" placeholder="Optional description of the agent" rows="4"></textarea>
			</div>
			<div class="form-group pb-4">
				<label class="font-nunito">Monitor URL</label>
				<input type="text" class="form-control" name="check_url" placeholder="https://mywebsite.test/health-check" autocomplete="off">
			</div>
			<div class="form-group pb-4">
				<label class="font-nunito">Monitor Text</label>
				<input type="text" class="form-control" name="check_text" placeholder="Hi, we're online and ready to help!" autocomplete="off">
				<p class="form-text small text-muted">Optional text or phrase on the page to detect if it's online.</p>
			</div>
			<div class="form-group pb-4">
				<label class="font-nunito">Check Frequency</label>
				<input type="range" class="custom-range" name="frequency" id="freq" min="5" max="60" step="5" value="15">
				<p class="form-text small text-muted">
					<span>Check every: </span>
					<span id="freqLabel" class="font-nunito font-weight-bold">15 minutes</span>
				</p>
			</div>
			<div class="form-group pb-4">
				<div class="custom-control custom-switch">
					<input type="checkbox" class="custom-control-input" name="active" id="isActive">
					<label class="custom-control-label" for="isActive">
						<span class="font-nunito font-weight-bold">Active</span>
					</label>
				</div>
			</div>		
			<hr>
			<div class="form-group text-right mb-0">
				<button type="submit" class="btn btn-primary font-nunito font-weight-bold">Create</button>
			</div>
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