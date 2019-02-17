@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="d-flex align-items-center justify-content-between mb-0">
	<div>
		<p class="h3 font-nunito font-weight-bold mb-0">New Incident</p>
		<p class="lead font-nunito mb-0">Creating a new incident</p>
	</div>
	<div>
		<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.incidents')}}"><i class="fas fa-chevron-left mr-2"></i>Back</a>
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
				<option selected>Select a System Service</option>
				@foreach($services as $service)
				<option value="{{$service->id}}">System: {{$service->system->name}} | Service: {{$service->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label class="font-nunito">Title</label>
			<input type="text" class="form-control" name="title" placeholder="Incident Title" autocomplete="off">
		</div>
		<hr>
		<div class="form-group text-right mb-0">
			<button type="submit" class="btn btn-primary font-nunito font-weight-bold">Create</button>
		</div>
	</form>
</div>

@endsection