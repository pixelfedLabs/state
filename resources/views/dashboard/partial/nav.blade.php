<ul class="nav nav-tabs nav-fill">
	<li class="nav-item">
		<a class="nav-link font-nunito {{request()->is('dashboard/home')?'active':''}}" href="{{route('dashboard.home')}}">Home</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito {{request()->is('dashboard/systems*')?'active':''}}" href="{{route('dashboard.systems')}}">Systems</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito {{request()->is('dashboard/services*')?'active':''}}" href="{{route('dashboard.services')}}">Services</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito {{request()->is('dashboard/incidents*')?'active':''}}" href="{{route('dashboard.incidents')}}">Incidents</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito {{request()->is('dashboard/agents*')?'active':''}}" href="{{route('dashboard.agents')}}">Agents</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito disabled" href="#" tabindex="-1" aria-disabled="true">Workers</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito" href="#">Notifications</a>
	</li>
	<li class="nav-item">
		<a class="nav-link font-nunito disabled" href="#" tabindex="-1" aria-disabled="true">Settings</a>
	</li>
</ul>