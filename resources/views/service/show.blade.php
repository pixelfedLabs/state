@extends('layouts.app')

@section('content')

<service-page id="{{$service->id}}" slug="{{$service->slug}}"></service-page>

@endsection