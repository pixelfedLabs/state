<template>
	
<div class="container mt-3">

	<p class="h4 text-center pb-5 font-nunito">Status Monitoring for <span class="font-weight-bold">pixelfed.social</span></p>

	<div :class="systemHealth.class">
		<span class="d-inline text-white h3 mb-0" v-html="systemHealth.message">
		</span>
	</div>

	<div class="my-5">
		<h2 class="pb-2">Current Status</h2>
		<div class="list-group box-shadow">
			<div class="list-group-item d-flex justify-content-between py-3" v-for="(service, index) in services">
				<div>
					<span class="lead font-weight-bold mr-2">{{service.name}}</span>
					<span data-toggle="tooltip" :title="service.description"><i class="far fa-question-circle"></i></span>
				</div>
				<div v-html="stateToText(service.state)">
					Loading...
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="my-5 w-100">
			<p class="h2 pb-3">Past Incidents</p>
			<div class="col-12 incidents-list">
				<div v-if="incidents" v-for="(incident, index) in incidents" class="status-day">
					<div class="media pb-5">
						<span class="incident-icon text-white mr-3">
							<i class="far p-0 fa-calendar"></i>
						</span>
						<div class="media-body mb-3">
							<p class="h4 font-weight-bold">{{humanDate(incident.date)}}</p>
							<div v-if="index == 1" class="card card-body box-shadow">
								<p class="lead font-weight-bold">Incident on {{humanDate(incident.date)}}</p>
								<p class="mb-0">
									<span class="text-success font-weight-bold">Resolved</span>
									<span class="mx-2">-</span>
									<span>We have deployed changes to address API issues. We do not expect any more issues related to this incident.</span>
									<p class="text-muted small">6:42pm</p>
								</p>
								<p class="mb-0">
									<span class="font-weight-bold text-dark">Update</span>
									<span class="mx-2">-</span>
									<span>We continue to investigate the issue.</span>
									<p class="text-muted small">6:20pm</p>
								</p>
								<p class="mb-0">
									<span class="text-warning font-weight-bold">Investigating</span>
									<span class="mx-2">-</span>
									<span>We are investigating reports of API issues.</span>
									<p class="text-muted small mb-0">6:00pm</p>
								</p>
							</div>
							<div v-else class="lead text-muted font-weight-lighter">
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
					<a class="font-nunito font-weight-bold text-dark px-2" href="#">About</a>
					<a class="font-nunito font-weight-bold text-dark px-2" href="#">API</a>
					<a class="font-nunito font-weight-bold text-dark px-2" href="#">Source</a>
					<span class="font-nunito font-weight-bold text-lighter px-2">Powered by state</span>
				</p>
			</div>
		</div>
	</div>
	
</div>

</template>

<style type="text/css" scoped>
.incident-icon {
	display: inline-flex;
	width: 44px;
	height: 44px;
	background-color: #343a40 !important;
	border-radius: 50%;
	align-items: center;
	justify-content: center;
	z-index: 3;
}

.status-day::before {
    position: absolute;
    top: 0;
    left: 34px;
    display: block;
    content: "";
    width: 3px;
    height: 100%;
    background-color: #e6ebf1;
    z-index: 1;
}

.incidents-footer {
	display: block;
	width: 100%;
	border-bottom: 3px solid #e6ebf1;
}
</style>

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
				services: [
					{
						name: 'Website',
						state: 'ok',
						lastChecked: false,
						description: 'Performance of Website and Platform'
					},
					{
						name: 'API Requests',
						state: 'ok',
						lastChecked: false,
						description: 'Requests for Pixelfed APIs'
					},
					{
						name: 'Federation Service',
						state: 'ok',
						lastChecked: false,
						description: 'Performance of ActivityPub APIs and Endpoints'
					},
					{
						name: 'Notification Service',
						state: 'ok',
						lastChecked: false,
						description: 'Performance of Notifications and Real Time Alerts'
					},
					{
						name: 'Transcode Service',
						state: 'unknown',
						lastChecked: false,
						description: 'Performance of Transcoding Service, responsible for optimizing photos and video'
					}					
				],
				incidents: []
			}
		},

		mounted() {
			$('[data-toggle="tooltip"]').tooltip();
			this.populatePastTwoWeeks();
			this.services.map(item => {
				if(item.state != 'ok') {
					if(item.state == 'degraded' && this.systemHealth.state == 'outage') {
						return;
					}
					this.systemHealthToggle(item.state);
				}
			});
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

			populatePastTwoWeeks() {
				let result = [];

				for (let i = 0; i < 14; i++) {
					var d = new Date();
					d.setDate(d.getDate() - i);
					result.push({
						date: this.formatDate(d),
						incidents: {}
					});
				}

				this.incidents = result;
			},

			humanDate(date) {
				return moment(date).format('MMM DD YYYY');
			}

		},
	}
</script>