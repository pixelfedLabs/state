<template>
	
<div v-if="services.length" class="container mt-3">

	<p class="h4 text-center pb-5 font-nunito">Status Monitoring for <a class="font-weight-bold text-dark" :href="services[0].website">{{services[0].domain}}</a></p>

	<div :class="systemHealth.class">
		<span class="d-inline text-white h3 mb-0 font-nunito font-weight-bold" v-html="systemHealth.message">
		</span>
	</div>

	<div class="my-5">
		<h2 class="pb-2 font-nunito font-weight-bold">Current Status</h2>
		<div class="list-group">
			<div class="list-group-item py-3" v-for="(service, index) in services">
				<div class="d-flex justify-content-between">
					<div>
						<a class="lead font-weight-bold mr-2 text-dark" :href="service.url">{{service.name}}</a>
						<span data-toggle="tooltip" :title="service.tooltip"><i class="far fa-question-circle"></i></span>
					</div>
					<div v-html="stateToText(service.state)">
						Loading...
					</div>
				</div>
				<uptime-graph :id="service.agent"></uptime-graph>
			</div>
		</div>
	</div>

	<div class="">
		<div class="my-5 w-100">
			<p class="h2 pb-3 font-nunito font-weight-bold">Past Incidents</p>
			<div class="col-12 incidents-list">
				<div v-if="incidents" v-for="i in incidents" class="status-day">
					<div class="media pb-5">
						<span class="incident-icon text-white mr-3">
							<i class="far p-0 fa-calendar"></i>
						</span>
						<div class="media-body mb-3">
							<p class="h4 font-weight-bold">{{humanDate(i.date)}}</p>
							<div v-if="i.incidents.length && index <= 2" v-for="(incident, index) in i.incidents" class="card card-body box-shadow mb-2">
								<div v-if="incident.state == 'resolved'">
									<p class="lead font-weight-bold">Resolved Incident: {{incident.title}}</p>
									<p class="mb-0 lead">View <a :href="incident.url">Incident</a> Report</p>
								</div>
								<div v-if="incident.state != 'resolved' && incident.updates.length">
									<p class="lead font-weight-bold">Incident: {{incident.title}}</p>
									<div v-for="update in incident.updates" class="row mt-3">
										<div class="col-12 col-md-3">
											<div class="font-weight-bold">{{update.state}}</div>
										</div>
										<div class="col-12 col-md-9">
										<div>{{update.description}}</div>
										<p class="small mb-0"><a :href="update.url" class="text-muted">{{update.created_at}}</a></p>
										</div>
									</div>
								</div>									
							</div>
							<div v-if="i.incidents.length > 3">
								<p class="text-center font-nunito font-weight-bold"><a :href="i.incidents[0].day_url" class="text-muted">View More</a></p>
							</div>
							<div v-if="!i.incidents.length" class="lead text-muted font-weight-lighter">
								No incidents reported.
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 incidents-footer"></div>
			<div class="py-3">
				<a href="#" class="lead font-weight-lighter"><i class="fas fa-chevron-left pr-2"></i> Previous incidents</a>
			</div>
			<div class="pt-5">
				<p class="text-center">
					<a class="font-nunito font-weight-bold text-dark px-2" href="/site/about">About</a>
					<a class="font-nunito font-weight-bold text-dark px-2" href="#">API</a>
					<a class="font-nunito font-weight-bold text-dark px-2" href="https://github.com/dansup/state">Source</a>
					<span class="font-nunito font-weight-bold text-lighter px-2">Powered by state</span>
				</p>
			</div>
		</div>
	</div>
	
</div>

</template>

<script type="text/javascript">
	export default {

		data() {
			return {
				state: 'ok',
				systemHealth: {
					class: 'card bg-success card-body py-3',
					message: '<i class="fas fa-check"></i> <span>All Systems Operational</span>',
					state: 'ok'
				},
				systems: [],
				services: [],
				incidents: []

			}
		},

		beforeMount() {
			this.fetchServices();
		},

		mounted() {
			this.services.map(item => {
				if(item.state != 'ok') {
					if(item.state == 'degraded' && this.systemHealth.state == 'outage') {
						return;
					}
					this.systemHealthToggle(item.state);
				}
			});

			let self = this;
			setInterval(function() {
				self.fetchSystems();
			}, 1000 * 60 * 15);
		},

		updated() {
			$('[data-toggle="tooltip"]').tooltip();
		},

		methods: {

			stateToText(state) {
				switch(state) {
					case 'ok':
						return '<span class="text-success font-weight-bold">Operational</span>';
					break;

					case 'degraded':
						return '<span class="text-warning font-weight-bold"><i class="fas fa-exclamation mr-1"></i> Degraded</span>';
					break;

					case 'outage':
						return '<span class="text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Outage</span>';
					break;

					case 'unknown':
						return '<span class="text-lighter font-weight-bold"><i class="fas fa-question-circle mr-1"></i> Unknown Status</span>';
					break;
				}
			},

			systemHealthToggle(state) {
				let operational = '<i class="fas fa-check mr-1"></i> <span>All Systems Operational</span>';
				let degraded = '<i class="fas fa-exclamation mr-1"></i> <span>Partial Degraded Service</span>';
				let outage = '<i class="fas fa-exclamation-triangle mr-1"></i> <span>System Outage</span>';

				switch(state) {
					case 'ok':
					case 'operational':
						this.systemHealth.class = 'card bg-success card-body py-3';
						this.systemHealth.message = operational;
						this.systemHealth.state = 'ok';
					break;

					case 'degraded':
						this.systemHealth.class = 'card bg-warning card-body py-3';
						this.systemHealth.message = degraded;
						this.systemHealth.state = 'degraded';
					break;

					case 'outage':
						this.systemHealth.class = 'card bg-danger card-body py-3';
						this.systemHealth.message = outage;
						this.systemHealth.state = 'outage';
					break;
				}
			},

			formatDate(date) {
				let dd = date.getDate();
				let mm = date.getMonth() + 1;
				let yyyy = date.getFullYear();
				if(dd < 10) {
					dd = '0' + dd
				}
				if(mm < 10) {
					mm = '0' + mm
				}
				date = mm + '/' + dd + '/' + yyyy;
				return date
			},

			humanDate(date) {
				return moment(date).format('MMM DD YYYY');
			},

			fetchIncidents()
			{
				axios.get('/api/v1/incidents')
					.then(res => {
						this.incidents = res.data;
					});
			},

			fetchServices() {
				axios.get('/api/v1/services')
					.then(res => {
						this.services = res.data;
						this.fetchIncidents();
					})
			},
		},
	}
</script>