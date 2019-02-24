@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="d-flex align-items-center justify-content-between mb-0">
	<div>
		<p class="h3 font-nunito font-weight-bold mb-0">Incident Update</p>
		<p class="lead font-nunito mb-0">Incident # {{$incident->id}} | Update # {{$update->id}}</p>
	</div>
	<div>
		<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.incidents')}}"><i class="fas fa-chevron-left mr-2"></i>Back</a>
		<a class="btn btn-primary font-nunito font-weight-bold" href="{{$incident->url()}}">View</a>
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
		<input type="hidden" name="_method" value="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="form-group">
			<label class="font-nunito">Service</label>
			<select class="custom-select" name="service" disabled>
				<option selected>Select a System Service</option>
				@foreach($services as $service)
				<option value="{{$service->id}}" {{$service->id == $update->service_id?'selected':''}}>Service: {{$service->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label class="font-nunito">Description</label>
			<textarea class="form-control" name="description" rows="4">{{$update->description}}</textarea>
		</div>
		<div class="form-group">
			<label class="font-nunito">State</label>
			<select class="custom-select" name="state">
				<option value="investigating" {{$update->state =='investigating'?'selected':''}}>Investigating</option>
				<option value="update" {{$update->state =='update'?'selected':''}}>Update</option>
				<option value="resolved" {{$update->state =='resolved'?'selected':''}}>Resolved</option>
			</select>
		</div>
		<hr>
		<div class="d-flex justify-content-between align-items-center">
			<a href="{{$update->url()}}" class="text-truncate">{{$update->url()}}</a>
			<div>
				<button type="button" class="btn btn-outline-danger font-nunito font-weight-bold mr-2 btn-delete">Delete</button>
				<button type="submit" class="btn btn-primary font-nunito font-weight-bold">Update</button>
			</div>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
	$('.btn-delete').on('click', function(e) {
		e.preventDefault();
		$('[name="_method"]').val('DELETE');
		$('form').submit();
	});
});
</script>
@endpush