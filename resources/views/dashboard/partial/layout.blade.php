@extends('layouts.app')

@section('content')
<div class="container">
	
@include('dashboard.partial.nav')

<div class="card card-body card-top-nav">
@yield('page')
</div>

</div>
<div class="pt-5">
	<p class="text-center">
		<a class="font-nunito font-weight-bold text-dark px-2" href="/site/about">About</a>
		<a class="font-nunito font-weight-bold text-dark px-2" href="#">API</a>
		<a class="font-nunito font-weight-bold text-dark px-2" href="https://github.com/dansup/state">Source</a>
		<span class="font-nunito font-weight-bold text-lighter px-2">Powered by state</span>
	</p>
</div>

@endsection