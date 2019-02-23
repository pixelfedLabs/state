<template>
	<div>
		<div class="pt-1 text-center">
			<a v-for="n in uptime.res" :class="n.class" data-toggle="tooltip" :title="tooltip(n)" :href="n.url"></a>
		</div>
		<div class="d-flex justify-content-between w-100">
			<p class="font-nunito text-muted mb-1">{{uptime.range}} days ago</p>
			<p class="font-nunito text-muted mb-1">{{uptime.percent}}% uptime</p>
			<p class="font-nunito text-muted mb-1">Today</p>
		</div>
	</div>
</template>

<style type="text/css" scoped>
	.ug {
		display:inline-block; 
		height:40px; 
		width:5px; 
		background: #38c172;
		margin-right:4px;
	}

	.state-ok {

	}

	.state-degraded {
		background: #ffc200;
	}

	.state-outage {
		background-color: #ff7217;
	}

	.state-nodata {
		background-color: #DAE1E7;
	}
</style>

<script type="text/javascript">
	
export default {
	props: ['id'],

	data() {
		return {
			uptime: {
				range: 30,
				all: [],
				percent: 100,
				res: []
			},
			win: {
				width: document.documentElement.clientWidth
			}
		};
	},

	beforeMount() {
		let width = document.documentElement.clientWidth;
		if(width > 992) {
			this.uptime.range = 90;
		} else if(width > 767) {
			this.uptime.range = 60;
		} else {
			this.uptime.range = 30;
		}
	},

	mounted() {
		this.fetchData();
		//window.addEventListener('resize', this.onResize);
	},

	updated() {
		$('[data-toggle="tooltip"]').tooltip({html: true});
	},

	methods: {
		fetchData() {
			let apiUrl = '/api/v1/services/uptime/' + this.id;
			axios.get(apiUrl, {
				params: {
					days: this.uptime.range
				}
			}).then(res => {
				this.uptime.all = res.data;
				this.uptime.res = res.data;
				this.onResize();
			});
		},

		onResize(init = false) {
		},

		refresh() {
			window.location.href = window.location.href;
		},

		tooltip(n) {
			if(n.class == "ug state-nodata") {
				return 'No data available';
			}
			let days = n.daysAgo == 0 ? 'Today': n.daysAgo + ' days ago';
			days = days + '<br><span class="small">(' + n.uptime_percent + '% uptime)</span>';
			return days;
		}
	}

}

</script>