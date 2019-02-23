@extends('layouts.app')

@section('content')
<uptime-page slug="{{$slug}}" date="{{$date}}" year="{{$year}}" month="{{$month}}" day="{{$day}}"></uptime-page>
@endsection