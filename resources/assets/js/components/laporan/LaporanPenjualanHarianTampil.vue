<script>
	import {Line} from 'vue-chartjs'

	export default {
		extends: Line,
		data: function () {
			return {
				filter: {
					dari_tanggal: '',
					sampai_tanggal: '',
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "laporan-penjualan-harian"),
			}
		},
		mounted () {
			var app = this;
			var dari_tanggal = app.$route.params.dari_tanggal;
			var sampai_tanggal = app.$route.params.sampai_tanggal;

			app.filter.dari_tanggal = dari_tanggal;
			app.filter.sampai_tanggal = sampai_tanggal;

			app.barChart();
		},
		methods: {	
			barChart(){
				var app = this;
				var filter = app.filter;
				axios.get(app.url+'/view/'+filter.dari_tanggal+'/'+filter.sampai_tanggal)
				.then(function (resp) {
					app.renderChart(
					{
						labels: resp.data.total,
						datasets: [{
							label: 'LAPORAN HARIAN PENJUALAN POS',
							backgroundColor: resp.data.color,
							data: resp.data.tanggal,
						}]
					},
					{
						responsive: true, maintainAspectRatio: false
					})

				})
			}
		}
	}
</script>