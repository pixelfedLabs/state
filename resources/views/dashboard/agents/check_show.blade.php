@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
	<div class="d-flex align-items-center justify-content-between mb-0">
		<div>
			<p class="h3 font-nunito font-weight-bold mb-0">Uptime Scan Result</p>
			<p class="lead font-nunito mb-0">ID: {{$check->id}}</p>
		</div>
		<div>
			<a class="btn btn-outline-secondary font-nunito font-weight-bold" href="{{route('dashboard.agents')}}"><i class="fas fa-chevron-left mr-2"></i>Back</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="card mb-3">
				<div class="card-body align-middle text-center py-5">
					<p class="display-4 font-weight-lighter mb-0">{{$check->response_code}}</p>
					<p class="mb-0 text-muted">{{$check->online == true ? 'ok' : 'unavailable'}}</p>
				</div>
			</div>
			<div class="list-group">
				<div class="list-group-item">
					<span class="font-weight-bold pr-3">System</span>
					<span class="word-break"><a href="{{$agent->system->url()}}">{{$agent->system->name}}</a></span>
				</div>
				<div class="list-group-item">
					<span class="font-weight-bold pr-3">Service</span>
					<span class="word-break"><a href="{{$agent->service->url()}}">{{$agent->service->name}}</a></span>
				</div>
				<div class="list-group-item">
					<span class="font-weight-bold pr-3">Agent</span>
					<span class="word-break"><a href="{{$agent->url()}}">{{$agent->name}}</a></span>
				</div>
				<div class="list-group-item">
					<span class="font-weight-bold pr-3">Monitor URL</span>
					<span class="word-break"><a href="{{$agent->check_url}}">{{$agent->check_url}}</a></span>
				</div>
				<div class="list-group-item">
					<span class="font-weight-bold pr-3">Scan Frequency</span>
					<span class="word-break">Every {{$agent->frequency}} minutes</span>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="card">
				<div class="card-header bg-light font-nunito font-weight-bold text-center">Response Headers</div>
				<div class="list-group list-group-flush" style="height:395px; overflow-y: auto;">
					@foreach($headers as $k => $v)
					@if($k == 'Set-Cookie')
					 @continue
					 @endif
					<li class="list-group-item">
						<span class="font-weight-bold pr-3">{{$k}}</span>
						<span class="word-break">{{$v}}</span>
					</li>
					@endforeach
				</div>
			</div>
		</div>
	</div>

</div>

@endsection