@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
	<div class="d-flex align-items-center justify-content-between mb-0">
		<div>
			<p class="h3 font-nunito font-weight-bold mb-0">New Service</p>
			<p class="lead font-nunito mb-0">Creating a new service</p>
		</div>
		<div>
			<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.services')}}"><i class="fas fa-chevron-left mr-2"></i>Back</a>
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
				<label class="font-nunito">System</label>
				<select class="custom-select" name="system">
					<option selected>Select a System</option>
					@foreach(App\System::get() as $system)
					<option value="{{$system->id}}">{{$system->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<label class="font-nunito">Name</label>
				<input type="text" class="form-control" name="name" placeholder="Service name (ex: Website or API)" autocomplete="off">
			</div>
			<div class="form-group">
				<label class="font-nunito">Description</label>
				<textarea class="form-control" name="description" placeholder="Brief description of the service" rows="4"></textarea>
			</div>
			<div class="form-group">
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

	@endsection