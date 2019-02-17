@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <p class="h3 mb-0 font-nunito">Hello, {{Auth::user()->name}}</p>
                    <p class="lead font-nunito">Welcome to state, to get started you can select an option below.</p>
                    <p class="mb-0">
                        <a class="btn btn-outline-primary btn-sm py-1 font-nunito font-weight-bold mx-2" href="#">Dashboard</a>
                        <a class="btn btn-outline-primary btn-sm py-1 font-nunito font-weight-bold mx-2" href="#">Add Service</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
