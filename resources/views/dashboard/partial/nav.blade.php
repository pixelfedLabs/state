<ul class="nav nav-tabs nav-fill">
  <li class="nav-item">
    <a class="nav-link {{request()->is('dashboard/home')?'active':''}}" href="{{route('dashboard.home')}}">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('dashboard/systems')?'active':''}}" href="{{route('dashboard.systems')}}">Systems</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('dashboard/services')?'active':''}}" href="{{route('dashboard.services')}}">Services</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{request()->is('dashboard/incidents')?'active':''}}" href="{{route('dashboard.incidents')}}">Incidents</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Settings</a>
  </li>
</ul>