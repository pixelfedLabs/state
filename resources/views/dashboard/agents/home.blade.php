@extends('dashboard.partial.layout')

@section('page')
<div class="p-md-4">
	
<div class="d-flex align-items-center justify-content-between mb-0">
	<div>
		<p class="h3 font-nunito font-weight-bold mb-0">Agents</p>
		<p class="lead font-nunito mb-0">Agents perform uptime monitoring for Systems and Services</p>
	</div>
	<div>
		<a class="btn btn-primary font-nunito font-weight-bold" href="{{route('dashboard.agents.create')}}"><i class="fas fa-plus-circle mr-2"></i>New Agent</a>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-12 col-md-4">
		<div class="card">
			<div class="card-body align-middle">
				<div class="py-3 text-center">
					<p class="display-4 font-nunito mb-0">0</p>
					<p class="font-nunito font-weight-bold text-muted mb-0">Active Agents</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-8">
		<div class="card box-shadow">
			<div class="card-body align-middle py-3">

			</div>
			<div class="mx-5 py-1">
			</div>
		</div>
	</div>
</div>
<hr>
<div>
<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Monitor URL</th>
      <th scope="col">Frequency</th>
      <th scope="col">Active</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">
      	<a href="#">1</a>
      </th>
      <td>Mark</td>
      <td>Otto</td>
      <td>Otto</td>
      <td>Otto</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>the Bird</td>
      <td>the Bird</td>
    </tr>
  </tbody>
</table>


</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
@endpush