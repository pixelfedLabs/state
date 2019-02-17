@extends('layouts.app')

@section('content')
<div class="container">
	
@include('dashboard.partial.nav')

<div class="card card-body card-top-nav">
@yield('page')
</div>

</div>
@endsection